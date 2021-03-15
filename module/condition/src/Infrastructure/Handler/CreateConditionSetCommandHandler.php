<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Infrastructure\Handler;

use Ergonode\Condition\Domain\Command\CreateConditionSetCommand;
use Ergonode\Condition\Domain\Entity\ConditionSet;
use Ergonode\Condition\Domain\Repository\ConditionSetRepositoryInterface;

class CreateConditionSetCommandHandler
{
    private ConditionSetRepositoryInterface $repository;

    public function __construct(ConditionSetRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(CreateConditionSetCommand $command): void
    {
        $segment = new ConditionSet($command->getId(), $command->getConditions());

        $this->repository->save($segment);
    }
}
