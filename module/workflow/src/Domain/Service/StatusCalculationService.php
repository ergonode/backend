<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Service;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Workflow\Domain\Entity\Transition;
use Ergonode\Workflow\Domain\Calculator\WorkflowConditionCalculator;
use Ergonode\Core\Domain\ValueObject\Language;

class StatusCalculationService
{
    private WorkflowConditionCalculator $calculator;

    public function __construct(WorkflowConditionCalculator $calculator)
    {
        $this->calculator = $calculator;
    }

    public function available(Transition $transition, AbstractProduct $product, Language $language): bool
    {
        return $this->calculator->calculate($transition, $product, $language);
    }
}
