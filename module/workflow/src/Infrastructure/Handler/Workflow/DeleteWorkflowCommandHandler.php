<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Handler\Workflow;

use Ergonode\Workflow\Domain\Command\Workflow\DeleteWorkflowCommand;
use Ergonode\Workflow\Domain\Repository\WorkflowRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class DeleteWorkflowCommandHandler
{
    /**
     * @var WorkflowRepositoryInterface
     */
    private $repository;

    /**
     * @param WorkflowRepositoryInterface $repository
     */
    public function __construct(WorkflowRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param DeleteWorkflowCommand $command
     *
     * @throws \ReflectionException
     */
    public function __invoke(DeleteWorkflowCommand $command)
    {
        $status = $this->repository->load($command->getId());
        Assert::notNull($status);

        $this->repository->delete($status);
    }
}
