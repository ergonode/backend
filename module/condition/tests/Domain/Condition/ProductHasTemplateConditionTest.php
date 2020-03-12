<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Tests\Domain\Condition;

use Ergonode\Condition\Domain\Condition\ProductHasTemplateCondition;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;

/**
 */
class ProductHasTemplateConditionTest extends TestCase
{
    /**
     */
    public function testConditionCreation(): void
    {
        $operator = 'some operator';
        $value = $this->createMock(TemplateId::class);
        $condition = new ProductHasTemplateCondition($operator, $value);

        $this->assertSame($operator, $condition->getOperator());
        $this->assertSame($value, $condition->getTemplateId());
        $this->assertSame('PRODUCT_HAS_TEMPLATE_CONDITION', $condition->getType());
    }

    /**
     */
    public function testGetSupportedOperators(): void
    {
        $operators = ProductHasTemplateCondition::getSupportedOperators();
        $this->assertIsArray($operators);
        $this->assertTrue(count($operators) >= 2);
    }
}
