<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Infrastructure\Condition;

use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;

interface ConditionCalculatorStrategyInterface
{
    public function supports(string $type): bool;

    public function calculate(AbstractProduct $object, ConditionInterface $configuration): bool;
}
