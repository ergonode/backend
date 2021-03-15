<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Tests\Domain\Condition;

use Ergonode\Condition\Domain\Condition\RoleExactlyCondition;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RoleExactlyConditionTest extends TestCase
{
    public function testConditionCreation(): void
    {
        /** @var RoleId | MockObject $roleId */
        $roleId = $this->createMock(RoleId::class);

        $condition = new RoleExactlyCondition($roleId);

        $this->assertSame($roleId, $condition->getRole());
        $this->assertSame(RoleExactlyCondition::TYPE, $condition->getType());
    }
}
