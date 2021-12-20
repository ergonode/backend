<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Handler\Workflow;

use Ergonode\Workflow\Domain\Repository\WorkflowRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Workflow\Domain\Command\Workflow\UpdateWorkflowTransitionConditionsCommand;

class UpdateWorkflowTransitionConditionsCommandHandler
{
    private WorkflowRepositoryInterface $repository;

    public function __construct(WorkflowRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(UpdateWorkflowTransitionConditionsCommand $command): void
    {
        $workflow = $this->repository->load($command->getId());

        Assert::notNull($workflow);

        $from = $command->getFrom();
        $to = $command->getTo();

        $workflow->changeTransitionConditions($from, $to, $command->getConditions());

        $this->repository->save($workflow);
    }
}
