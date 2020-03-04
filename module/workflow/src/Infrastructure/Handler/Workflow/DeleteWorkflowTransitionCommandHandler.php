<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Handler\Workflow;

use Ergonode\Workflow\Domain\Command\Workflow\DeleteWorkflowTransitionCommand;
use Ergonode\Workflow\Domain\Repository\WorkflowRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class DeleteWorkflowTransitionCommandHandler
{
    /**
     * @var WorkflowRepositoryInterface
     */
    private WorkflowRepositoryInterface $repository;

    /**
     * @param WorkflowRepositoryInterface $repository
     */
    public function __construct(WorkflowRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param DeleteWorkflowTransitionCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(DeleteWorkflowTransitionCommand $command)
    {
        $workflow = $this->repository->load($command->getWorkflowId());
        Assert::notNull($workflow);

        $workflow->removeTransition($command->getSource(), $command->getDestination());

        $this->repository->save($workflow);
    }
}
