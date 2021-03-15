<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Tests\Domain\Condition;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\Condition\Domain\Condition\UserExactlyCondition;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UserExactlyConditionTest extends TestCase
{
    public function testConditionCreation(): void
    {
        /** @var UserId | MockObject $userId */
        $userId = $this->createMock(UserId::class);

        $condition = new UserExactlyCondition($userId);

        $this->assertSame($userId, $condition->getUser());
        $this->assertSame(UserExactlyCondition::TYPE, $condition->getType());
    }
}
