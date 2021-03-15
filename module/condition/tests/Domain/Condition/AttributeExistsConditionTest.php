<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Tests\Domain\Condition;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Condition\Domain\Condition\AttributeExistsCondition;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AttributeExistsConditionTest extends TestCase
{
    public function testConditionCreation(): void
    {
        /** @var AttributeId | MockObject $attributeId */
        $attributeId = $this->createMock(AttributeId::class);

        $condition = new AttributeExistsCondition($attributeId);

        $this->assertSame($attributeId, $condition->getAttribute());
        $this->assertSame(AttributeExistsCondition::TYPE, $condition->getType());
    }
}
