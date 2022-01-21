<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Application\Controller\Api\Transition;

use Ergonode\Workflow\Domain\Entity\Status;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Workflow\Domain\Entity\AbstractWorkflow;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Ergonode\SharedKernel\Application\Serializer\NormalizerInterface;
use Ergonode\Workflow\Infrastructure\Validator\WorkflowConditionValidatorBuilder;
use Ergonode\Workflow\Domain\Command\Workflow\UpdateWorkflowTransitionConditionsCommand;
use Ergonode\Api\Application\Exception\ViolationsHttpException;

/**
 * @Route(
 *     name="ergonode_workflow_transition_condition_change",
 *     path="/workflow/default/transitions/{from}/{to}/conditions",
 *     methods={"PUT"},
 *     requirements={
 *        "from"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}",
 *        "to"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"
 *     }
 * )
 */
class TransitionConditionsChangeAction
{
    private ValidatorInterface $validator;

    private NormalizerInterface $normalizer;

    private CommandBusInterface $commandBus;

    private WorkflowConditionValidatorBuilder $builder;

    public function __construct(
        ValidatorInterface $validator,
        NormalizerInterface $normalizer,
        CommandBusInterface $commandBus,
        WorkflowConditionValidatorBuilder $builder
    ) {
        $this->validator = $validator;
        $this->normalizer = $normalizer;
        $this->commandBus = $commandBus;
        $this->builder = $builder;
    }

    /**
     * @IsGranted("ERGONODE_ROLE_WORKFLOW_PUT_TRANSITION_CONDITION")
     *
     * @SWG\Tag(name="Workflow")
     * @SWG\Parameter(
     *     name="from",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="From status id",
     * )
     * @SWG\Parameter(
     *     name="to",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="To status id",
     * )
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
     *     description="Update workflow",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/transition_update")
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
     */
    public function __invoke(AbstractWorkflow $workflow, Status $from, Status $to, Request $request): void
    {
        $data = $request->request->all();

        $builder = $this->builder->build($data);

        $violations = $this->validator->validate($data, $builder);

        if (0 === $violations->count()) {
            $data['id'] = $workflow->getId()->getValue();
            $data['from'] = $from->getId()->getValue();
            $data['to'] = $to->getId()->getValue();

            /** @var UpdateWorkflowTransitionConditionsCommand $command */
            $command = $this->normalizer->denormalize($data, UpdateWorkflowTransitionConditionsCommand::class);
            $this->commandBus->dispatch($command);

            return;
        }

        throw new ViolationsHttpException($violations);
    }
}
