<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Handler\Workflow;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Workflow\Domain\Command\Workflow\UpdateWorkflowCommand;
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

        foreach ($command->getStatuses() as $status) {
            if (!$workflow->hasStatus($status)) {
                $workflow->addStatus($status);
            }
        }

        $statuses = new ArrayCollection($command->getStatuses());
        foreach ($workflow->getStatuses() as $status) {
            if ($statuses->contains($status)) {
                $workflow->removeStatus($status);
            }
        }

        $this->repository->save($workflow);
    }
}
