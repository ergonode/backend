<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Domain\Command;

use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Webmozart\Assert\Assert;

class UpdateConditionSetCommand implements ConditionCommandInterface
{
    private ConditionSetId $id;

    /**
     * @var ConditionInterface[]
     */
    private array $conditions;

    /**
     * @param ConditionInterface[] $conditions
     */
    public function __construct(ConditionSetId $id, array $conditions = [])
    {
        Assert::allIsInstanceOf($conditions, ConditionInterface::class);

        $this->id = $id;
        $this->conditions = $conditions;
    }

    public function getId(): ConditionSetId
    {
        return $this->id;
    }

    /**
     * @return ConditionInterface[]
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }
}
