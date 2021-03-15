<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Infrastructure\Provider;

use Ergonode\Condition\Infrastructure\Condition\ConditionValidatorStrategyInterface;

class ConditionConstraintProvider
{
    /**
     * @var ConditionValidatorStrategyInterface[] $strategies
     */
    private array $strategies;

    public function __construct(ConditionValidatorStrategyInterface ...$strategies)
    {
        $this->strategies = $strategies;
    }

    /**
     * @throws \OutOfBoundsException
     */
    public function resolve(string $type): ConditionValidatorStrategyInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($type)) {
                return $strategy;
            }
        }

        throw new \OutOfBoundsException(sprintf('Constraint by condition type "%s" not found', $type));
    }
}
