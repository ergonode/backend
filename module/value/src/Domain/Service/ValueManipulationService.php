<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Value\Domain\Service;

use Ergonode\Value\Domain\ValueObject\ValueInterface;

class ValueManipulationService
{
    /**
     * @var ValueUpdateStrategyInterface[]
     */
    private array $strategies;

    public function __construct(ValueUpdateStrategyInterface ...$strategies)
    {
        $this->strategies = $strategies;
    }

    public function calculate(ValueInterface $oldValue, ValueInterface $newValue): ValueInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->isSupported($oldValue)) {
                return $strategy->calculate($oldValue, $newValue);
            }
        }

        throw new \RuntimeException(sprintf('Can\'t find strategy for value type %s', get_class($oldValue)));
    }
}
