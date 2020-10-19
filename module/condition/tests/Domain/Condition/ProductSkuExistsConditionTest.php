<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Tests\Domain\Condition;

use PHPUnit\Framework\TestCase;
use Ergonode\Condition\Domain\Condition\ProductSkuExistsCondition;

/**
 */
class ProductSkuExistsConditionTest extends TestCase
{

    /**
     */
    public function testConditionCreation(): void
    {
        $operator = 'someOperator';
        $value = 'someValue';
        $condition = new ProductSkuExistsCondition($operator, $value);

        self::assertSame($operator, $condition->getOperator());
        self::assertSame($value, $condition->getValue());
        self::assertSame(ProductSkuExistsCondition::TYPE, $condition->getType());
    }
}
