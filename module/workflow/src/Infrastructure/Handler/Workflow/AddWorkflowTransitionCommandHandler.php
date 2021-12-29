<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Handler\Workflow;

use Ergonode\Workflow\Domain\Command\Workflow\AddWorkflowTransitionCommand;
use Ergonode\Workflow\Domain\Repository\WorkflowRepositoryInterface;
use Webmozart\Assert\Assert;

class AddWorkflowTransitionCommandHandler
{
    private WorkflowRepositoryInterface $repository;

    public function __construct(WorkflowRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(AddWorkflowTransitionCommand $command): void
    {
        $roleIds = $command->getRoleIds();
        $conditionSetId = $command->getConditionSetId();
        $workflow = $this->repository->load($command->getWorkflowId());
        Assert::notNull($workflow);

        $from = $command->getFrom();
        $to = $command->getTo();

        if (!$workflow->hasStatus($from)) {
            $workflow->addStatus($from);
        }

        if (!$workflow->hasStatus($to)) {
            $workflow->addStatus($to);
        }

        $workflow->addTransition($command->getFrom(), $command->getTo());
        if ($conditionSetId) {
            $workflow->getTransition($from, $to)->changeConditionSetId($conditionSetId);
        }

        if (!empty($roleIds)) {
            $workflow->getTransition($from, $to)->changeRoleIds($roleIds);
        }

        $this->repository->save($workflow);
    }
}
