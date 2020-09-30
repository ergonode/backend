<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Application\Controller\Api\Workflow;

use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\Workflow\Domain\Entity\Workflow;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Workflow\Application\Provider\WorkflowFormProvider;
use Ergonode\Workflow\Infrastructure\Provider\CreateWorkflowCommandFactoryProvider;

/**
 * @Route(
 *     name="ergonode_workflow_create",
 *     path="/workflow",
 *     methods={"POST"}
 * )
 */
class WorkflowCreateAction
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
     * @var CreateWorkflowCommandFactoryProvider
     */
    private CreateWorkflowCommandFactoryProvider $commandProvider;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param FormFactoryInterface                 $formFactory
     * @param WorkflowFormProvider                 $formProvider
     * @param CreateWorkflowCommandFactoryProvider $commandProvider
     * @param CommandBusInterface                  $commandBus
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        WorkflowFormProvider $formProvider,
        CreateWorkflowCommandFactoryProvider $commandProvider,
        CommandBusInterface $commandBus
    ) {
        $this->formFactory = $formFactory;
        $this->formProvider = $formProvider;
        $this->commandProvider = $commandProvider;
        $this->commandBus = $commandBus;
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
     *     default="en_GB"
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Add workflow",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/workflow")
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
    public function __invoke(Request $request): Response
    {
        $type = $request->request->get('type', Workflow::DEFAULT);

        $class = $this->formProvider->provide($type);
        $request->request->remove('type');
        try {
            $form = $this->formFactory->create($class, null, ['validation_groups' => ['Default', 'Create']]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $command = $this->commandProvider->provide($type)->create($form);
                $this->commandBus->dispatch($command);

                return new CreatedResponse($command->getId());
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }
}
