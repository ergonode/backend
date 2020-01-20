<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Infrastructure\Provider;

use Ergonode\Condition\Infrastructure\Condition\ConditionCalculatorStrategyInterface;

/**
 */
class ConditionCalculatorProvider
{
    /**
     * @var ConditionCalculatorStrategyInterface[]
     */
    private array $strategies;

    /**
     * @param ConditionCalculatorStrategyInterface ...$strategies
     */
    public function __construct(ConditionCalculatorStrategyInterface ...$strategies)
    {
        $this->strategies = $strategies;
    }

    /**
     * @param string $type
     *
     * @return ConditionCalculatorStrategyInterface
     */
    public function provide(string $type): ConditionCalculatorStrategyInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($type)) {
                return $strategy;
            }
        }

        throw new \RuntimeException(sprintf('Can\' find calculation strategy for %s', $type));
    }
}
