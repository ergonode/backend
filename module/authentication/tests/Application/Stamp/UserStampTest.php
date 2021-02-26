<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Authentication\Tests\Application\Stamp;

use Ergonode\Authentication\Application\Stamp\UserStamp;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use PHPUnit\Framework\TestCase;

class UserStampTest extends TestCase
{
    public function testCreation(): void
    {
        $userId = $this->createMock(UserId::class);
        $userStamp = new UserStamp($userId);

        self::assertSame($userStamp->getUserId(), $userId);
    }
}
