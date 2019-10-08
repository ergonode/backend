<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Application\Controller\Api;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Core\Infrastructure\Builder\ExistingRelationshipMessageBuilderInterface;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Ergonode\Grid\Renderer\GridRenderer;
use Ergonode\Grid\RequestGridConfiguration;
use Ergonode\Workflow\Application\Form\Model\StatusFormModel;
use Ergonode\Workflow\Application\Form\StatusForm;
use Ergonode\Workflow\Domain\Command\Status\CreateStatusCommand;
use Ergonode\Workflow\Domain\Command\Status\DeleteStatusCommand;
use Ergonode\Workflow\Domain\Command\Status\UpdateStatusCommand;
use Ergonode\Workflow\Domain\Entity\Status;
use Ergonode\Workflow\Domain\Query\StatusQueryInterface;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use Ergonode\Workflow\Infrastructure\Grid\StatusGrid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;

/**
 */
class StatusController extends AbstractController
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
     * @var RelationshipsResolverInterface
     */
    private $relationshipsResolver;

    /**
     * @var ExistingRelationshipMessageBuilderInterface
     */
    private $existingRelationshipMessageBuilder;

    /**
     * @var GridRenderer
     */
    private $gridRenderer;

    /**
     * @param GridRenderer                                $gridRenderer
     * @param MessageBusInterface                         $messageBus
     * @param StatusQueryInterface                        $query
     * @param StatusGrid                                  $grid
     * @param RelationshipsResolverInterface              $relationshipsResolver
     * @param ExistingRelationshipMessageBuilderInterface $existingRelationshipMessageBuilder
     */
    public function __construct(
        GridRenderer $gridRenderer,
        MessageBusInterface $messageBus,
        StatusQueryInterface $query,
        StatusGrid $grid,
        RelationshipsResolverInterface $relationshipsResolver,
        ExistingRelationshipMessageBuilderInterface $existingRelationshipMessageBuilder
    ) {
        $this->messageBus = $messageBus;
        $this->query = $query;
        $this->grid = $grid;
        $this->relationshipsResolver = $relationshipsResolver;
        $this->existingRelationshipMessageBuilder = $existingRelationshipMessageBuilder;
        $this->gridRenderer = $gridRenderer;
    }

    /**
     * @Route("/status", methods={"GET"})
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
     *     description="Language Code"
     * )
     * @SWG\Parameter(
     *     name="limit",
     *     in="query",
     *     type="integer",
     *     required=true,
     *     default="50",
     *     description="Number of returned lines"
     * )
     * @SWG\Parameter(
     *     name="offset",
     *     in="query",
     *     type="integer",
     *     required=true,
     *     default="0",
     *     description="Number of start line"
     * )
     * @SWG\Parameter(
     *     name="field",
     *     in="query",
     *     required=false,
     *     type="string",
     *     description="Order field"
     * )
     * @SWG\Parameter(
     *     name="order",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"ASC","DESC"},
     *     description="Order"
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
     *     description="Returns statuses collection"
     * )
     *
     * @ParamConverter(class="Ergonode\Grid\RequestGridConfiguration")
     *
     * @param Language                 $language
     * @param RequestGridConfiguration $configuration
     *
     * @return Response
     */
    public function getStatuses(Language $language, RequestGridConfiguration $configuration): Response
    {
        $data = $this->gridRenderer->render(
            $this->grid,
            $configuration,
            $this->query->getDataSet($language),
            $language
        );

        return new SuccessResponse($data);
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
     *     description="Status id"
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns status"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found"
     * )
     *
     * @ParamConverter(class="Ergonode\Workflow\Domain\Entity\Status")
     *
     * @param Status $status
     *
     * @return Response
     */
    public function getStatus(Status $status): Response
    {
        return new SuccessResponse($status);
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
     *     response=201,
     *     description="Returns status ID"
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
            $model = new StatusFormModel();
            $form = $this->createForm(StatusForm::class, $model);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var StatusFormModel $data */
                $data = $form->getData();

                $command = new CreateStatusCommand(
                    new StatusCode($data->code),
                    $data->color,
                    new TranslatableString($data->name),
                    new TranslatableString($data->description)
                );

                $this->messageBus->dispatch($command);

                return new CreatedResponse($command->getId());
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
     *     description="Status code"
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
     * @ParamConverter(class="Ergonode\Workflow\Domain\Entity\Status")
     *
     * @param Status  $status
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function updateStatus(Status $status, Request $request): Response
    {
        try {
            $model = new StatusFormModel();
            $form = $this->createForm(StatusForm::class, $model, ['method' => Request::METHOD_PUT]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var StatusFormModel $data */
                $data = $form->getData();

                $command = new UpdateStatusCommand(
                    $status->getId(),
                    $data->color,
                    new TranslatableString($data->name),
                    new TranslatableString($data->description)
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
     *     description="Status code"
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
     * @SWG\Response(
     *     response=400,
     *     description="Request status parameter not correct"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Status not found"
     * )
     * @SWG\Response(
     *     response="409",
     *     description="Existing relationships"
     * )
     *
     * @ParamConverter(class="Ergonode\Workflow\Domain\Entity\Status")
     *
     * @param Status $status
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function deleteStatus(Status $status): Response
    {
        $relationships = $this->relationshipsResolver->resolve($status->getId());
        if (!$relationships->isEmpty()) {
            throw new ConflictHttpException($this->existingRelationshipMessageBuilder->build($relationships));
        }

        $command = new DeleteStatusCommand($status->getId());
        $this->messageBus->dispatch($command);

        return new EmptyResponse();
    }
}
