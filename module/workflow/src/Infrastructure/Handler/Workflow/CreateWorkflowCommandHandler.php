<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Handler\Workflow;

use Ergonode\Workflow\Domain\Command\Workflow\CreateWorkflowCommand;
use Ergonode\Workflow\Domain\Factory\WorkflowFactory;
use Ergonode\Workflow\Domain\Repository\WorkflowRepositoryInterface;

class CreateWorkflowCommandHandler
{
    private WorkflowRepositoryInterface $repository;

    private WorkflowFactory $factory;

    public function __construct(WorkflowRepositoryInterface $repository, WorkflowFactory $factory)
    {
        $this->repository = $repository;
        $this->factory = $factory;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(CreateWorkflowCommand $command): void
    {
        $workflow = $this->factory->create($command->getId(), $command->getCode(), $command->getStatuses());

        $this->repository->save($workflow);
    }
}
