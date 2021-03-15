<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Handler\Workflow;

use Ergonode\Workflow\Domain\Command\Status\SetDefaultStatusCommand;
use Ergonode\Workflow\Domain\Repository\WorkflowRepositoryInterface;
use Webmozart\Assert\Assert;

class WorkflowDefaultStatusSetCommandHandler
{
    private WorkflowRepositoryInterface $repository;

    public function __construct(WorkflowRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(SetDefaultStatusCommand $command): void
    {
        $workflow = $this->repository->load($command->getWorkflowId());
        Assert::notNull($workflow);

        $statusId = $command->getStatusId();

        if (!$workflow->hasStatus($statusId)) {
            $workflow->addStatus($statusId);
        }

        $workflow->setDefaultStatus($statusId);

        $this->repository->save($workflow);
    }
}
