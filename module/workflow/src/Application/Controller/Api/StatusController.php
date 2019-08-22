<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Application\Controller\Api;

use Ergonode\Core\Application\Exception\FormValidationHttpException;
use Ergonode\Core\Application\Response\CreatedResponse;
use Ergonode\Core\Application\Response\EmptyResponse;
use Ergonode\Core\Application\Response\SuccessResponse;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Grid\RequestGridConfiguration;
use Ergonode\Grid\Response\GridResponse;
use Ergonode\Workflow\Application\Form\Model\StatusFormModel;
use Ergonode\Workflow\Application\Form\StatusForm;
use Ergonode\Workflow\Domain\Command\Status\CreateStatusCommand;
use Ergonode\Workflow\Domain\Command\Status\DeleteStatusCommand;
use Ergonode\Workflow\Domain\Command\Status\UpdateStatusCommand;
use Ergonode\Workflow\Domain\Provider\WorkflowProvider;
use Ergonode\Workflow\Domain\Query\StatusQueryInterface;
use Ergonode\Workflow\Domain\ValueObject\Status;
use Ergonode\Workflow\Infrastructure\Grid\StatusGrid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;

/**
 */
class StatusController extends AbstractController
{
    /**
     * @var WorkflowProvider
     */
    private $provider;

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
     * @param WorkflowProvider     $provider
     * @param MessageBusInterface  $messageBus
     * @param StatusQueryInterface $query
     * @param StatusGrid           $grid
     */
    public function __construct(WorkflowProvider $provider, MessageBusInterface $messageBus, StatusQueryInterface $query, StatusGrid $grid)
    {
        $this->provider = $provider;
        $this->messageBus = $messageBus;
        $this->query = $query;
        $this->grid = $grid;
    }

    /**
     * @Route("/workflow/default/status", methods={"GET"})
     *
     * @IsGranted("WORKFLOW_READ")
     *
     * @SWG\Tag(name="Workflow")
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
     *
     * @param Language $language
     * @param Request  $request
     *
     * @return Response
     */
    public function getAttributes(Language $language, Request $request): Response
    {
        $configuration = new RequestGridConfiguration($request);
        $dataSet = $this->query->getDataSet($language);

        return new GridResponse($this->grid, $configuration, $dataSet, $language);
    }

    /**
     * @Route("/workflow/default/status/{status}", methods={"GET"})
     *
     * @IsGranted("WORKFLOW_READ")
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
     * @param string $status
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function getStatus(string $status): Response
    {
        $workflow = $this->provider->provide();

        if ($workflow && $workflow->hasStatus($status)) {
            return new SuccessResponse($workflow->getStatus($status));
        }

        throw new NotFoundHttpException();
    }

    /**
     * @Route("/workflow/default/status", methods={"POST"})
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
     *     response=201,
     *     description="Returns workflow ID",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
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
            $workflow = $this->provider->provide();

            $model = new StatusFormModel();
            $form = $this->createForm(StatusForm::class, $model);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var StatusFormModel $data */
                $data = $form->getData();

                $command = new CreateStatusCommand(
                    $workflow->getId(),
                    $data->code,
                    new Status($data->color, new TranslatableString($data->name), new TranslatableString($data->description))
                );

                $this->messageBus->dispatch($command);

                return new CreatedResponse($command->getId()->getValue());
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }

    /**
     * @Route("/workflow/default/status/{status}", methods={"PUT"})
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
     *     description="Update workflow",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/status")
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Success"
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
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

                return new EmptyResponse();
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }

    /**
     * @Route("/workflow/default/status/{status}", methods={"DELETE"})
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
     *     response=204,
     *     description="Success"
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

        return new EmptyResponse();
    }
}
