<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Service;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Workflow\Domain\Entity\Transition;
use Ergonode\Workflow\Domain\Calculator\WorkflowConditionCalculator;

class StatusCalculationService
{
    private WorkflowConditionCalculator $calculator;

    public function __construct(WorkflowConditionCalculator $calculator)
    {
        $this->calculator = $calculator;
    }

    public function available(Transition $transition, AbstractProduct $product): bool
    {
        return $this->calculator->calculate($transition, $product);
    }
}
