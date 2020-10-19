<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Tests\Domain\Condition;

use Ergonode\Condition\Domain\Condition\ProductHasStatusCondition;
use PHPUnit\Framework\TestCase;

/**
 */
class ProductHasStatusConditionTest extends TestCase
{
    /**
     */
    public function testConditionCreation(): void
    {
        $operator = 'some operator';
        $value = ['some value'];
        $condition = new ProductHasStatusCondition($operator, $value);

        self::assertSame($operator, $condition->getOperator());
        self::assertSame($value, $condition->getValue());
        self::assertSame('PRODUCT_HAS_STATUS_CONDITION', $condition->getType());
    }

    /**
     */
    public function testGetSupportedOperators()
    {
        $operators = ProductHasStatusCondition::getSupportedOperators();
        self::assertIsArray($operators);
        self::assertTrue(count($operators) >= 2);
    }
}
