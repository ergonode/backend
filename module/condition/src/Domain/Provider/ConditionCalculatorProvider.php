<?php

namespace Ergonode\Condition\Domain\Provider;

use Ergonode\Condition\Domain\Service\ConditionCalculatorStrategyInterface;

/**
 */
class ConditionCalculatorProvider
{
    /**
     * @var ConditionCalculatorStrategyInterface[]
     */
    private $strategies;

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
            if ($strategy->isSupportedBy($type)) {
                return $strategy;
            }
        }

        throw new \RuntimeException(sprintf('Can\' find calculation strategy for %s', $type));
    }
}
