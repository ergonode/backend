<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Domain\Service;

use Ergonode\Condition\Domain\Entity\ConditionSet;
use Ergonode\Condition\Infrastructure\Provider\ConditionCalculatorProvider;
use Ergonode\Product\Domain\Entity\AbstractProduct;

class ConditionCalculator
{
    private ConditionCalculatorProvider $provider;

    public function __construct(ConditionCalculatorProvider $provider)
    {
        $this->provider = $provider;
    }

    public function calculate(ConditionSet $conditionSet, AbstractProduct $product): bool
    {
        foreach ($conditionSet->getConditions() as $condition) {
            $calculator = $this->provider->provide($condition->getType());
            if (!$calculator->calculate($product, $condition)) {
                return false;
            }
        }

        return true;
    }
}
