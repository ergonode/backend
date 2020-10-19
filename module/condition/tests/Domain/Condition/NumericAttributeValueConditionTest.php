<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Tests\Domain\Condition;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Condition\Domain\Condition\NumericAttributeValueCondition;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class NumericAttributeValueConditionTest extends TestCase
{
    /**
     */
    public function testConditionCreation(): void
    {
        /** @var AttributeId | MockObject $attributeId */
        $attributeId = $this->createMock(AttributeId::class);
        $operator = 'string';
        $value = 1.2;

        $condition = new NumericAttributeValueCondition($attributeId, $operator, $value);
        self::assertSame($attributeId, $condition->getAttribute());
        self::assertSame($operator, $condition->getOption());
        self::assertSame($value, $condition->getValue());
        self::assertSame(NumericAttributeValueCondition::TYPE, $condition->getType());
    }
}
