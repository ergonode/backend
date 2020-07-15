<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Application\Controller\Api\Workflow;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Ergonode\Workflow\Application\Provider\WorkflowFormProvider;
use Ergonode\Api\Application\Response\CreatedResponse;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Workflow\Domain\Entity\AbstractWorkflow;
use Ergonode\Workflow\Infrastructure\Provider\UpdateWorkflowCommandFactoryProvider;

/**
 * @Route(
 *     name="ergonode_workflow_change",
 *     path="/workflow/default",
 *     methods={"PUT"}
 * )
 */
class WorkflowChangeAction
{
    /**
     * @var FormFactoryInterface
     */
    private FormFactoryInterface $formFactory;

    /**
     * @var WorkflowFormProvider
     */
    private WorkflowFormProvider $formProvider;

    /**
     * @var UpdateWorkflowCommandFactoryProvider
     */
    private UpdateWorkflowCommandFactoryProvider $commandProvider;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param FormFactoryInterface                 $formFactory
     * @param WorkflowFormProvider                 $formProvider
     * @param UpdateWorkflowCommandFactoryProvider $commandProvider
     * @param CommandBusInterface                  $commandBus
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        WorkflowFormProvider $formProvider,
        UpdateWorkflowCommandFactoryProvider $commandProvider,
        CommandBusInterface $commandBus
    ) {
        $this->formFactory = $formFactory;
        $this->formProvider = $formProvider;
        $this->commandProvider = $commandProvider;
        $this->commandBus = $commandBus;
    }

    /**
     * @IsGranted("WORKFLOW_UPDATE")
     *
     * @SWG\Tag(name="Workflow")
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Add attribute",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/workflow")
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en",
     *     description="Language Code",
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
     * @param AbstractWorkflow $workflow
     * @param Request          $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function __invoke(AbstractWorkflow $workflow, Request $request): Response
    {
        $class = $this->formProvider->provide($workflow->getType());
        try {
            $form = $this->formFactory->create($class, null, ['method' => Request::METHOD_PUT]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $command = $this->commandProvider->provide($workflow->getType())->create($workflow->getId(), $form);
                $this->commandBus->dispatch($command);

                return new CreatedResponse($command->getId());
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }
}
