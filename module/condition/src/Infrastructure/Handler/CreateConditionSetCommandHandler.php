<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Infrastructure\Handler;

use Ergonode\Condition\Domain\Command\CreateConditionSetCommand;
use Ergonode\Condition\Domain\Entity\ConditionSet;
use Ergonode\Condition\Domain\Repository\ConditionSetRepositoryInterface;
use Ergonode\Segment\Domain\Entity\Segment;

/**
 */
class CreateConditionSetCommandHandler
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
     * @param CreateConditionSetCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(CreateConditionSetCommand $command)
    {
        $segment = new ConditionSet(
            $command->getId(),
            $command->getCode(),
            $command->getName(),
            $command->getDescription()
        );

        $this->repository->save($segment);
    }
}
