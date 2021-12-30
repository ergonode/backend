<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Handler\Workflow;

use Ergonode\Condition\Domain\Command\DeleteConditionSetCommand;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Workflow\Domain\Command\Workflow\UpdateWorkflowCommand;
use Ergonode\Workflow\Domain\Entity\AbstractWorkflow;
use Ergonode\Workflow\Domain\Repository\WorkflowRepositoryInterface;
use Webmozart\Assert\Assert;

class UpdateWorkflowCommandHandler
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
    public function __invoke(UpdateWorkflowCommand $command): void
    {
        $workflow = $this->repository->load($command->getId());

        Assert::notNull($workflow);

        $this->updateStatuses($command->getStatuses(), $workflow);
        $conditionSetIds = $this->updateTransitions($command->getTransitions(), $workflow);
        $defaultStatus = $command->getDefaultStatus();

        if ($defaultStatus) {
            if (!$workflow->hasStatus($defaultStatus)) {
                $workflow->addStatus($defaultStatus);
            }

            $workflow->setDefaultStatus($defaultStatus);
        }

        $this->repository->save($workflow);
        $this->deleteConditionSet($conditionSetIds);
    }

    private function updateStatuses(array $commandStatuses, AbstractWorkflow $workflow): void
    {
        foreach ($workflow->getStatuses() as $status) {
            $contains = false;
            foreach ($commandStatuses as $commandStatus) {
                if ($status->getValue() === $commandStatus->getValue()) {
                    $contains = true;
                }
            }
            if (!$contains) {
                $workflow->removeStatus($status);
            }
        }

        foreach ($commandStatuses as $status) {
            if (!$workflow->hasStatus($status)) {
                $workflow->addStatus($status);
            }
        }
    }

    /**
     * @return ConditionSetId[]
     */
    private function updateTransitions(array $commandTransitions, AbstractWorkflow $workflow): array
    {
        $conditionSetIds = [];
        foreach ($workflow->getTransitions() as $transition) {
            $contains = false;
            foreach ($commandTransitions as $commandTransition) {
                if ($transition->getFrom()->getValue() === $commandTransition['from']->getValue() &&
                    $transition->getTo()->getValue() === $commandTransition['to']->getValue()) {
                    $contains = true;
                }
            }
            if (!$contains) {
                $workflow->removeTransition($transition->getFrom(), $transition->getTo());
                if ($transition->getConditionSetId()) {
                    $conditionSetIds[] = $transition->getConditionSetId();
                }
            }
        }

        foreach ($commandTransitions as $transition) {
            if (!$workflow->hasTransition($transition['from'], $transition['to'])) {
                $workflow->addTransition($transition['from'], $transition['to']);
                if (isset($transition['condition_set'])) {
                    $workflow->changeTransitionConditionSetId(
                        $transition['from'],
                        $transition['to'],
                        $transition['condition_set']
                    );
                }
                if (isset($transition['roles'])) {
                    $workflow->changeTransitionRoleIds(
                        $transition['from'],
                        $transition['to'],
                        $transition['roles']
                    );
                }
            }
        }

        return array_unique($conditionSetIds);
    }

    /**
     * @param ConditionSetId[] $conditionSetIds
     */
    private function deleteConditionSet(array $conditionSetIds): void
    {
        foreach ($conditionSetIds as $conditionSetId) {
            if (null === $this->relationshipsResolver->resolve($conditionSetId)) {
                $this->commandBus->dispatch(
                    new DeleteConditionSetCommand($conditionSetId),
                );
            }
        }
    }
}
