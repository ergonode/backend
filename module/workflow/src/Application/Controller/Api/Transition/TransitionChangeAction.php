<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Application\Controller\Api\Transition;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Condition\Domain\Entity\ConditionSetId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Workflow\Application\Form\Model\TransitionChangeFormModel;
use Ergonode\Workflow\Application\Form\TransitionChangeForm;
use Ergonode\Workflow\Domain\Command\Workflow\UpdateWorkflowTransitionCommand;
use Ergonode\Workflow\Domain\Entity\Status;
use Ergonode\Workflow\Domain\Entity\Workflow;
use Ergonode\Workflow\Domain\ValueObject\Transition;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_workflow_transition_change",
 *     path="/workflow/default/transitions/{source}/{destination}",
 *     methods={"PUT"}
 * )
 */
class TransitionChangeAction
{
    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @param MessageBusInterface  $messageBus
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(
        MessageBusInterface $messageBus,
        FormFactoryInterface $formFactory
    ) {
        $this->messageBus = $messageBus;
        $this->formFactory = $formFactory;
    }

    /**
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
    public function __invoke(Workflow $workflow, Status $source, Status $destination, Request $request): Response
    {
        try {
            $model = new TransitionChangeFormModel();
            $form = $this->formFactory->create(TransitionChangeForm::class, $model, ['method' => Request::METHOD_PUT]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var TransitionChangeFormModel $data */
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
}
