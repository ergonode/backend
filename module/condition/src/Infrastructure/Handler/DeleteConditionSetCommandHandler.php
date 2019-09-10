<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Infrastructure\Handler;

use Ergonode\Condition\Domain\Command\DeleteConditionSetCommand;
use Ergonode\Condition\Domain\Repository\ConditionSetRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class DeleteConditionSetCommandHandler
{
    /**
     * @var ConditionSetRepositoryInterface
     */
    private $repository;

    /**
     * @param ConditionSetRepositoryInterface $repository
     */
    public function __construct(ConditionSetRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param DeleteConditionSetCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(DeleteConditionSetCommand $command)
    {
        $conditionSet = $this->repository->load($command->getId());
        Assert::notNull($conditionSet);

        $this->repository->delete($conditionSet);
    }
}
