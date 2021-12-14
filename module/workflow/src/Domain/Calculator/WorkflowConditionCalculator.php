<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Calculator;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Workflow\Domain\Entity\Transition;

class WorkflowConditionCalculator
{
    public function calculate(Transition $transition, AbstractProduct $product): bool
    {
        return true;
    }
}
