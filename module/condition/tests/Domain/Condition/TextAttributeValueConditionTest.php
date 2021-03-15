<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Tests\Domain\Condition;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Condition\Domain\Condition\TextAttributeValueCondition;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TextAttributeValueConditionTest extends TestCase
{
    public function testConditionCreation(): void
    {
        /** @var AttributeId | MockObject $attributeId */
        $attributeId = $this->createMock(AttributeId::class);
        $operator = 'operator';
        $value = 'value';

        $condition = new TextAttributeValueCondition($attributeId, $operator, $value);

        $this->assertSame($attributeId, $condition->getAttribute());
        $this->assertSame($operator, $condition->getOption());
        $this->assertSame($value, $condition->getValue());
        $this->assertSame(TextAttributeValueCondition::TYPE, $condition->getType());
    }
}
