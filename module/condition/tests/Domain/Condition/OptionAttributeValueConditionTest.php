<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Tests\Domain\Condition;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Condition\Domain\Condition\OptionAttributeValueCondition;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class OptionAttributeValueConditionTest extends TestCase
{
    /**
     */
    public function testConditionCreation(): void
    {
        /** @var AttributeId | MockObject $attributeId */
        $attributeId = $this->createMock(AttributeId::class);
        $value = 'value';

        $condition = new OptionAttributeValueCondition($attributeId, $value);

        self::assertSame($attributeId, $condition->getAttribute());
        self::assertSame($value, $condition->getValue());
        self::assertSame(OptionAttributeValueCondition::TYPE, $condition->getType());
    }
}
