<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Infrastructure\Handler;

use Ergonode\Condition\Domain\Command\UpdateConditionSetCommand;
use Ergonode\Condition\Domain\Repository\ConditionSetRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class UpdateConditionSetCommandHandler
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
     * @param UpdateConditionSetCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(UpdateConditionSetCommand $command)
    {
        $conditionSet = $this->repository->load($command->getId());
        Assert::notNull($conditionSet);

        $conditionSet->changeConditions($command->getConditions());

        if ($command->hasName()) {
            $conditionSet->changeName($command->getName());
        }

        if ($command->hasDescription()) {
            $conditionSet->changeDescription($command->getDescription());
        }

        $this->repository->save($conditionSet);
    }
}
