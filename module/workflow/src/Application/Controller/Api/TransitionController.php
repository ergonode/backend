<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Application\Controller\Api;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Condition\Domain\Entity\ConditionSetId;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Grid\Renderer\GridRenderer;
use Ergonode\Grid\RequestGridConfiguration;
use Ergonode\Workflow\Application\Form\Model\TransitionFormModel;
use Ergonode\Workflow\Application\Form\TransitionForm;
use Ergonode\Workflow\Domain\Command\Workflow\AddWorkflowTransitionCommand;
use Ergonode\Workflow\Domain\Command\Workflow\DeleteWorkflowTransitionCommand;
use Ergonode\Workflow\Domain\Command\Workflow\UpdateWorkflowTransitionCommand;
use Ergonode\Workflow\Domain\Entity\Status;
use Ergonode\Workflow\Domain\Entity\Workflow;
use Ergonode\Workflow\Domain\Query\TransitionQueryInterface;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use Ergonode\Workflow\Domain\ValueObject\Transition;
use Ergonode\Workflow\Infrastructure\Grid\TransitionGrid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
class TransitionController extends AbstractController
{
    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var TransitionQueryInterface
     */
    private $query;

    /**
     * @var TransitionGrid
     */
    private $grid;

    /**
     * @var GridRenderer
     */
    private $gridRenderer;

    /**
     * @param GridRenderer             $gridRenderer
     * @param MessageBusInterface      $messageBus
     * @param TransitionQueryInterface $query
     * @param TransitionGrid           $grid
     */
    public function __construct(
        GridRenderer $gridRenderer,
        MessageBusInterface $messageBus,
        TransitionQueryInterface $query,
        TransitionGrid $grid
    ) {
        $this->messageBus = $messageBus;
        $this->query = $query;
        $this->grid = $grid;
        $this->gridRenderer = $gridRenderer;
    }

    /**
     * @Route("/workflow/default/transitions", methods={"GET"})
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
     * @ParamConverter(class="Ergonode\Workflow\Domain\Entity\Workflow")
     * @ParamConverter(class="Ergonode\Grid\RequestGridConfiguration")
     *
     * @param Workflow                 $workflow
     * @param Language                 $language
     * @param RequestGridConfiguration $configuration
     *
     * @return Response
     */
    public function getTransitions(Workflow $workflow, Language $language, RequestGridConfiguration $configuration): Response
    {
        $data = $this->gridRenderer->render(
            $this->grid,
            $configuration,
            $this->query->getDataSet($workflow->getId(), $language),
            $language
        );

        return new SuccessResponse($data);
    }

    /**
     * @Route(
     *     name="ergonode_workflow_transition_read",
     *     path="/workflow/default/transitions/{source}/{destination}",
     *     methods={"GET"}
     * )
     *
     * @IsGranted("WORKFLOW_READ")
     *
     * @SWG\Tag(name="Workflow")
     * @SWG\Parameter(
     *     name="source",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="Source status code",
     * )
     * @SWG\Parameter(
     *     name="destination",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="Destination status code",
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
     * @ParamConverter(class="Ergonode\Workflow\Domain\Entity\Workflow")
     * @ParamConverter(class="Ergonode\Workflow\Domain\Entity\Status", name="source")
     * @ParamConverter(class="Ergonode\Workflow\Domain\Entity\Status", name="destination")
     *
     * @param Workflow $workflow
     * @param Status   $source
     * @param Status   $destination
     *
     * @return Response
     */
    public function getTransition(Workflow $workflow, Status $source, Status $destination): Response
    {
        if ($workflow->hasTransition($source->getCode(), $destination->getCode())) {
            return new SuccessResponse($workflow->getTransition($source->getCode(), $destination->getCode()));
        }

        throw new NotFoundHttpException();
    }

    /**
     * @Route("/workflow/default/transitions", methods={"POST"})
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
     *     @SWG\Schema(ref="#/definitions/transition")
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Returns status ID",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     *
     * @ParamConverter(class="Ergonode\Workflow\Domain\Entity\Workflow")
     *
     * @param Workflow $workflow
     * @param Request  $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function addTransition(Workflow $workflow, Request $request): Response
    {
        try {
            $model = new TransitionFormModel();
            $form = $this->createForm(TransitionForm::class, $model);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var TransitionFormModel $data */
                $data = $form->getData();

                $transition = new Transition(
                    new StatusCode($data->source),
                    new StatusCode($data->destination),
                    new TranslatableString($data->name),
                    new TranslatableString($data->description),
                    $data->conditionSet ? new ConditionSetId($data->conditionSet) : null
                );

                $command = new AddWorkflowTransitionCommand($workflow->getId(), $transition);

                $this->messageBus->dispatch($command);

                return new Response('', Response::HTTP_CREATED);
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }

    /**
     * @Route(
     *     name="ergonode_workflow_transition_change",
     *     path="/workflow/default/transitions/{source}/{destination}",
     *     methods={"PUT"}
     * )
     *
     * @IsGranted("WORKFLOW_UPDATE")
     *
     * @SWG\Tag(name="Workflow")
     * @SWG\Parameter(
     *     name="source",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="Source status code",
     * )
     * @SWG\Parameter(
     *     name="destination",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="Destination status code",
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
     *     @SWG\Schema(ref="#/definitions/transition")
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
     * @ParamConverter(class="Ergonode\Workflow\Domain\Entity\Status", name="source")
     * @ParamConverter(class="Ergonode\Workflow\Domain\Entity\Status", name="destination")
     *
     * @param Workflow $workflow
     * @param Status   $source
     * @param Status   $destination
     * @param Request  $request
     *
     * @return Response
     */
    public function changeTransition(Workflow $workflow, Status $source, Status $destination, Request $request): Response
    {
        try {
            $model = new TransitionFormModel();
            $form = $this->createForm(TransitionForm::class, $model, ['method' => Request::METHOD_PUT]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var TransitionFormModel $data */
                $data = $form->getData();

                $transition = new Transition(
                    $source->getCode(),
                    $destination->getCode(),
                    new TranslatableString($data->name),
                    new TranslatableString($data->description),
                    $data->conditionSet ? new ConditionSetId($data->conditionSet) : null
                );

                $command = new UpdateWorkflowTransitionCommand(
                    $workflow->getId(),
                    $transition
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
     * @Route(
     *     name="ergonode_workflow_transition_delete",
     *     path="/workflow/default/transitions/{source}/{destination}",
     *     methods={"DELETE"}
     * )
     *
     * @IsGranted("WORKFLOW_DELETE")
     *
     * @SWG\Tag(name="Workflow")
     * @SWG\Parameter(
     *     name="source",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="Source status code",
     * )
     * @SWG\Parameter(
     *     name="destination",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="Destination status code",
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
     *
     * @ParamConverter(class="Ergonode\Workflow\Domain\Entity\Status", name="source")
     * @ParamConverter(class="Ergonode\Workflow\Domain\Entity\Status", name="destination")
     *
     * @param Workflow $workflow
     * @param Status   $source
     * @param Status   $destination
     *
     * @return Response
     *
     */
    public function deleteStatus(Workflow $workflow, Status $source, Status $destination): Response
    {
        // @todo add validation
        $command = new DeleteWorkflowTransitionCommand($workflow->getId(), $source->getCode(), $destination->getCode());
        $this->messageBus->dispatch($command);

        return new EmptyResponse();
    }
}
