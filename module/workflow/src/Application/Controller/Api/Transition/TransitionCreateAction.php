<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Application\Controller\Api\Transition;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Condition\Domain\Entity\ConditionSetId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Workflow\Application\Form\Model\TransitionFormModel;
use Ergonode\Workflow\Application\Form\TransitionForm;
use Ergonode\Workflow\Domain\Command\Workflow\AddWorkflowTransitionCommand;
use Ergonode\Workflow\Domain\Entity\Workflow;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
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
 *     name="ergonode_workflow_transition_create",
 *     path="/workflow/default/transitions",
 *     methods={"POST"}
 * )
 */
class TransitionCreateAction
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
    public function __invoke(Workflow $workflow, Request $request): Response
    {
        try {
            $model = new TransitionFormModel();
            $form = $this->formFactory->create(TransitionForm::class, $model);
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
}
