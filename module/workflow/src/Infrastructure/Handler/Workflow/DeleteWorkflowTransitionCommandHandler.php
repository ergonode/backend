<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Handler\Workflow;

use Ergonode\Condition\Domain\Command\DeleteConditionSetCommand;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Workflow\Domain\Command\Workflow\DeleteWorkflowTransitionCommand;
use Ergonode\Workflow\Domain\Repository\WorkflowRepositoryInterface;
use Webmozart\Assert\Assert;

class DeleteWorkflowTransitionCommandHandler
{
    private WorkflowRepositoryInterface $repository;
    private RelationshipsResolverInterface $relationshipsResolver;
    private CommandBusInterface $commandBus;

    public function __construct(
        WorkflowRepositoryInterface $repository,
        RelationshipsResolverInterface $relationshipsResolver,
        CommandBusInterface $commandBus
    ) {
        $this->repository = $repository;
        $this->relationshipsResolver = $relationshipsResolver;
        $this->commandBus = $commandBus;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(DeleteWorkflowTransitionCommand $command): void
    {
        $workflow = $this->repository->load($command->getWorkflowId());
        Assert::notNull($workflow);

        $transition = $workflow->getTransition($command->getSource(), $command->getDestination());

        if ($transition->getConditionSetId() &&
            null === $this->relationshipsResolver->resolve($transition->getConditionSetId())
        ) {
            $this->commandBus->dispatch(
                new DeleteConditionSetCommand(
                    $transition->getConditionSetId(),
                ),
            );
        }

        $workflow->removeTransition($command->getSource(), $command->getDestination());

        $this->repository->save($workflow);
    }
}
