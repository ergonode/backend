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

        $this->assertSame($operator, $condition->getOperator());
        $this->assertSame($value, $condition->getValue());
        $this->assertSame('PRODUCT_HAS_STATUS_CONDITION', $condition->getType());
    }

    /**
     */
    public function testGetSupportedOperators()
    {
        $operators = ProductHasStatusCondition::getSupportedOperators();
        $this->assertIsArray($operators);
        $this->assertTrue(count($operators) >= 2);
    }
}
