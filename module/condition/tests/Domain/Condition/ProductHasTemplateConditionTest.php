<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Tests\Domain\Condition;

use Ergonode\Condition\Domain\Condition\ProductHasTemplateCondition;
use PHPUnit\Framework\TestCase;

/**
 */
class ProductHasTemplateConditionTest extends TestCase
{
    /**
     */
    public function testConditionCreation(): void
    {
        $operator = 'some operator';
        $value = 'some value';
        $condition = new ProductHasTemplateCondition($operator, $value);

        $this->assertSame($operator, $condition->getOperator());
        $this->assertSame($value, $condition->getValue());
        $this->assertSame('PRODUCT_HAS_TEMPLATE_CONDITION', $condition->getType());
    }

    /**
     */
    public function testGetSupportedOperators()
    {
        $operators = ProductHasTemplateCondition::getSupportedOperators();
        $this->assertIsArray($operators);
        $this->assertTrue(count($operators) >= 2);
    }
}
