<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Handler\Workflow;

use Ergonode\Workflow\Domain\Command\Workflow\UpdateWorkflowCommand;
use Ergonode\Workflow\Domain\Entity\AbstractWorkflow;
use Ergonode\Workflow\Domain\Repository\WorkflowRepositoryInterface;
use Webmozart\Assert\Assert;

class UpdateWorkflowCommandHandler
{
    private WorkflowRepositoryInterface $repository;

    public function __construct(WorkflowRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(UpdateWorkflowCommand $command): void
    {
        $workflow = $this->repository->load($command->getId());

        Assert::notNull($workflow);

        $this->updateStatuses($command->getStatuses(), $workflow);
        $this->updateTransitions($command->getTransitions(), $workflow);

        $this->repository->save($workflow);
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

    private function updateTransitions(array $commandTranitions, AbstractWorkflow $workflow): void
    {
        foreach ($workflow->getTransitions() as $transition) {
            $contains = false;
            foreach ($commandTranitions as $commandTransition) {
                if ($transition->getFrom()->getValue() === $commandTransition['source']->getValue() &&
                    $transition->getTo()->getValue() === $commandTransition['destination']->getValue()) {
                    $contains = true;
                }
            }
            if (!$contains) {
                $workflow->removeTransition($transition->getFrom(), $transition->getTo());
            }
        }

        foreach ($commandTranitions as $transition) {
            if (!$workflow->hasTransition($transition['source'], $transition['destination'])) {
                $workflow->addTransition($transition['source'], $transition['destination']);
                if (isset($transition['condition_set'])) {
                    $workflow->changeTransitionConditionSetId(
                        $transition['source'],
                        $transition['destination'],
                        $transition['condition_set']
                    );
                }
                if (isset($transition['roles'])) {
                    $workflow->changeTransitionRoleIds(
                        $transition['source'],
                        $transition['destination'],
                        $transition['roles']
                    );
                }
            }
        }
    }
}
