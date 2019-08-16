<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Handler\Status;

use Ergonode\Workflow\Domain\Command\Status\UpdateStatusCommand;
use Ergonode\Workflow\Domain\Repository\WorkflowRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class UpdateStatusCommandHandler
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
     * @param UpdateStatusCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(UpdateStatusCommand $command)
    {
        $workflow = $this->repository->load($command->getId());
        Assert::notNull($workflow);

        if (!$workflow->hasStatus($command->getCode())) {
            $workflow->changeStatus($command->getCode(), $command->getStatus());
        }

        $this->repository->save($workflow);
    }
}
