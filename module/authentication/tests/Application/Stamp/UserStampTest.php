<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Authentication\Tests\Application\Stamp;

use Ergonode\Authentication\Application\Stamp\UserStamp;
use Ergonode\Core\Domain\User\AggregateUserInterface;
use PHPUnit\Framework\TestCase;

class UserStampTest extends TestCase
{
    public function testCreation(): void
    {
        $user = $this->createMock(AggregateUserInterface::class);
        $userStamp = new UserStamp($user);

        self::assertSame($user, $userStamp->getUser());
    }
}
