<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Tests\Application\Messenger\Stamp;

use Ergonode\Core\Application\Messenger\Stamp\UserStamp;
use Ergonode\Core\Application\Security\User\CachedUser;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\SharedKernel\Domain\ValueObject\Email;
use Ergonode\Core\Domain\ValueObject\Language;

class UserStampTest extends TestCase
{
    public function testCreation(): void
    {
        $user = new CachedUser(
            $this->createMock(UserId::class),
            'Name',
            'Surname',
            $this->createMock(RoleId::class),
            $this->createMock(Email::class),
            $this->createMock(Language::class),
            true
        );

        $userStamp = new UserStamp($user);

        self::assertSame($user, $userStamp->getUser());
    }
}
