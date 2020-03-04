<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Handler\Workflow;

use Ergonode\Workflow\Domain\Command\Status\SetDefaultStatusCommand;
use Ergonode\Workflow\Domain\Repository\WorkflowRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class WorkflowDefaultStatusSetCommandHandler
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
     * @param SetDefaultStatusCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(SetDefaultStatusCommand $command)
    {
        $workflow = $this->repository->load($command->getWorkflowId());
        Assert::notNull($workflow);

        $status = $command->getStatusCode();

        if (!$workflow->hasStatus($status)) {
            $workflow->addStatus($status);
        }

        $workflow->setDefaultStatus($status);

        $this->repository->save($workflow);
    }
}
