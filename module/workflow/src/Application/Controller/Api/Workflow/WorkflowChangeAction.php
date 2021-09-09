<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Application\Controller\Api\Workflow;

use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Ergonode\Workflow\Application\Provider\WorkflowFormProvider;
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
    private FormFactoryInterface $formFactory;

    private WorkflowFormProvider $formProvider;

    private UpdateWorkflowCommandFactoryProvider $commandProvider;

    private CommandBusInterface $commandBus;

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
     * @IsGranted("ERGONODE_ROLE_WORKFLOW_PUT")
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
     *     default="en_GB",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Success"
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     * @throws \Exception
     */
    public function __invoke(AbstractWorkflow $workflow, Request $request): WorkflowId
    {
        $class = $this->formProvider->provide($workflow->getType());
        try {
            $form = $this->formFactory->create($class, null, ['method' => Request::METHOD_PUT]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $command = $this->commandProvider->provide($workflow->getType())->create($workflow->getId(), $form);
                $this->commandBus->dispatch($command);

                return $command->getId();
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }
}
