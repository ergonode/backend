<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Calculator;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Workflow\Infrastructure\Provider\WorkflowConditionCalculatorProvider;
use Ergonode\Workflow\Domain\Entity\Transition;
use Ergonode\Core\Domain\ValueObject\Language;

class WorkflowConditionCalculator
{
    private WorkflowConditionCalculatorProvider $provider;

    public function __construct(WorkflowConditionCalculatorProvider $provider)
    {
        $this->provider = $provider;
    }

    public function calculate(Transition $transition, AbstractProduct $product, Language $language): bool
    {
        foreach ($transition->getConditions() as $condition) {
            $calculator = $this->provider->provide($condition);
            if (!$calculator->calculate($product, $condition, $language)) {
                return false;
            }
        }

        return true;
    }
}
