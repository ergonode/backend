<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
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
        $defaultStatus = $command->getDefaultStatus();

        if ($defaultStatus) {
            if (!$workflow->hasStatus($defaultStatus)) {
                $workflow->addStatus($defaultStatus);
            }

            $workflow->setDefaultStatus($defaultStatus);
        }

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

    private function updateTransitions(array $commandTransitions, AbstractWorkflow $workflow): void
    {
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
            }
        }

        foreach ($commandTransitions as $transition) {
            if (!$workflow->hasTransition($transition['from'], $transition['to'])) {
                $workflow->addTransition($transition['from'], $transition['to']);
                if (isset($transition['roles'])) {
                    $workflow->changeTransitionRoleIds(
                        $transition['from'],
                        $transition['to'],
                        $transition['roles']
                    );
                }
            }
        }
    }
}
