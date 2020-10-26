<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Handler\Workflow;

use Ergonode\Workflow\Domain\Command\Workflow\DeleteWorkflowTransitionCommand;
use Ergonode\Workflow\Domain\Repository\WorkflowRepositoryInterface;
use Webmozart\Assert\Assert;

class DeleteWorkflowTransitionCommandHandler
{
    private WorkflowRepositoryInterface $repository;

    public function __construct(WorkflowRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(DeleteWorkflowTransitionCommand $command): void
    {
        $workflow = $this->repository->load($command->getWorkflowId());
        Assert::notNull($workflow);

        $workflow->removeTransition($command->getSource(), $command->getDestination());

        $this->repository->save($workflow);
    }
}
