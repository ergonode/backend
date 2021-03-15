<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Infrastructure\Handler;

use Ergonode\Condition\Domain\Command\UpdateConditionSetCommand;
use Ergonode\Condition\Domain\Repository\ConditionSetRepositoryInterface;
use Webmozart\Assert\Assert;

class UpdateConditionSetCommandHandler
{
    private ConditionSetRepositoryInterface $repository;

    public function __construct(ConditionSetRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(UpdateConditionSetCommand $command): void
    {
        $conditionSet = $this->repository->load($command->getId());
        Assert::notNull($conditionSet);

        $conditionSet->changeConditions($command->getConditions());

        $this->repository->save($conditionSet);
    }
}
