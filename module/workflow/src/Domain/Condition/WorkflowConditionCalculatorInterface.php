<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Condition;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Core\Domain\ValueObject\Language;

interface WorkflowConditionCalculatorInterface
{
    public function supports(WorkflowConditionInterface $condition): bool;

    public function calculate(
        AbstractProduct $product,
        WorkflowConditionInterface $condition,
        Language $language
    ): bool;
}
