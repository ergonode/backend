<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Handler\Status;

use Ergonode\Workflow\Domain\Command\Status\CreateStatusCommand;
use Ergonode\Workflow\Domain\Repository\WorkflowRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class CreateStatusCommandHandler
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
     * @param CreateStatusCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(CreateStatusCommand $command)
    {
        $workflow = $this->repository->load($command->getId());
        Assert::notNull($workflow);

        if (!$workflow->hasStatus($command->getCode())) {
            $workflow->addStatus($command->getCode(), $command->getStatus());
        }

        $this->repository->save($workflow);
    }
}
