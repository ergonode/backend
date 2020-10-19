<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Tests\Domain\Condition;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Condition\Domain\Condition\TextAttributeValueCondition;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class TextAttributeValueConditionTest extends TestCase
{
    /**
     */
    public function testConditionCreation(): void
    {
        /** @var AttributeId | MockObject $attributeId */
        $attributeId = $this->createMock(AttributeId::class);
        $operator = 'operator';
        $value = 'value';

        $condition = new TextAttributeValueCondition($attributeId, $operator, $value);

        self::assertSame($attributeId, $condition->getAttribute());
        self::assertSame($operator, $condition->getOption());
        self::assertSame($value, $condition->getValue());
        self::assertSame(TextAttributeValueCondition::TYPE, $condition->getType());
    }
}
