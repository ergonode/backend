<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Application\Controller\Api;

use Ergonode\Core\Application\Controller\AbstractApiController;
use Ergonode\Core\Application\Exception\FormValidationHttpException;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Grid\RequestGridConfiguration;
use Ergonode\Workflow\Application\Form\Model\StatusFormModel;
use Ergonode\Workflow\Application\Form\StatusForm;
use Ergonode\Workflow\Domain\Command\Status\CreateStatusCommand;
use Ergonode\Workflow\Domain\Command\Status\DeleteStatusCommand;
use Ergonode\Workflow\Domain\Command\Status\UpdateStatusCommand;
use Ergonode\Workflow\Domain\Entity\Status;
use Ergonode\Workflow\Domain\Query\StatusQueryInterface;
use Ergonode\Workflow\Infrastructure\Grid\StatusGrid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;

/**
 */
class StatusController extends AbstractApiController
{
    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var StatusQueryInterface
     */
    private $query;

    /**
     * @var StatusGrid
     */
    private $grid;

    /**
     * @param MessageBusInterface  $messageBus
     * @param StatusQueryInterface $query
     * @param StatusGrid           $grid
     */
    public function __construct(MessageBusInterface $messageBus, StatusQueryInterface $query, StatusGrid $grid)
    {
        $this->messageBus = $messageBus;
        $this->query = $query;
        $this->grid = $grid;
    }

    /**
     * @Route("/status", methods={"GET"})
     *
     * @IsGranted("WORKFLOW_READ")
     *
     * @SWG\Tag(name="Workflow")
     *
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Parameter(
     *     name="limit",
     *     in="query",
     *     type="integer",
     *     required=true,
     *     default="50",
     *     description="Number of returned lines",
     * )
     * @SWG\Parameter(
     *     name="offset",
     *     in="query",
     *     type="integer",
     *     required=true,
     *     default="0",
     *     description="Number of start line",
     * )
     * @SWG\Parameter(
     *     name="field",
     *     in="query",
     *     required=false,
     *     type="string",
     *     description="Order field",
     * )
     * @SWG\Parameter(
     *     name="order",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"ASC","DESC"},
     *     description="Order",
     * )
     * @SWG\Parameter(
     *     name="filter",
     *     in="query",
     *     required=false,
     *     type="string",
     *     description="Filter"
     * )
     * @SWG\Parameter(
     *     name="show",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"COLUMN","DATA"},
     *     description="Specify what response should containts"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns statuses collection",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @param Language $language
     * @param Request  $request
     *
     * @return Response
     */
    public function getStatuses(Language $language, Request $request): Response
    {
        $configuration = new RequestGridConfiguration($request);
        $dataSet = $this->query->getDataSet($language);
        $grid = $this->renderGrid($this->grid, $configuration, $dataSet, $language);

        return $this->createRestResponse($grid);
    }

    /**
     * @Route("/status/{status}", methods={"GET"}, requirements={"status" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     *
     * @IsGranted("WORKFLOW_READ")
     *
     * @SWG\Tag(name="Workflow")
     * @SWG\Parameter(
     *     name="status",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="Status id",
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns status",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @param Status $status
     *
     * @ParamConverter(class="Ergonode\Workflow\Domain\Entity\Status")
     *
     * @return Response
     */
    public function getStatus(Status $status): Response
    {
        return $this->createRestResponse($status);
    }

    /**
     * @Route("/status", methods={"POST"})
     *
     * @IsGranted("WORKFLOW_CREATE")
     *
     * @SWG\Tag(name="Workflow")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="EN"
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Add workflow",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/status")
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns workflow id",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function createStatus(Request $request): Response
    {
        try {
            $model = new StatusFormModel();
            $form = $this->createForm(StatusForm::class, $model);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var StatusFormModel $data */
                $data = $form->getData();

                $command = new CreateStatusCommand(
                    $data->code,
                    $data->color,
                    new TranslatableString($data->name),
                    new TranslatableString($data->description)
                );

                $this->messageBus->dispatch($command);

                return $this->createRestResponse(['id' => $command->getId()], [], Response::HTTP_CREATED);
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }

    /**
     * @Route("/status/{status}", methods={"PUT"})
     *
     * @IsGranted("WORKFLOW_UPDATE")
     *
     * @SWG\Tag(name="Workflow")
     * @SWG\Parameter(
     *     name="status",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="Status code",
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="EN"
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Update  workflow",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/status")
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns attribute",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @param string  $status
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function updateStatus(string $status, Request $request): Response
    {
        try {
            $workflow = $this->provider->provide();

            $model = new StatusFormModel();
            $form = $this->createForm(StatusForm::class, $model, ['method' => Request::METHOD_PUT]);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var StatusFormModel $data */
                $data = $form->getData();

                $command = new UpdateStatusCommand(
                    $workflow->getId(),
                    $status,
                    new Status($data->color, new TranslatableString($data->name), new TranslatableString($data->description))
                );

                $this->messageBus->dispatch($command);

                return $this->createRestResponse(['id' => $command->getId()], [], Response::HTTP_CREATED);
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }

    /**
     * @Route("/status/{status}", methods={"DELETE"})
     *
     * @IsGranted("WORKFLOW_DELETE")
     *
     * @SWG\Tag(name="Workflow")
     * @SWG\Parameter(
     *     name="status",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="Status code",
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="EN"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns attribute",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @param string $status
     *
     * @return Response
     *
     * @throws \Exception
     *
     * @todo add validation
     */
    public function deleteStatus(string $status): Response
    {
        $workflow = $this->provider->provide();

        $command = new DeleteStatusCommand($workflow->getId(), $status);

        $this->messageBus->dispatch($command);

        return $this->createRestResponse(['id' => $command->getId()], [], Response::HTTP_ACCEPTED);
    }
}
