<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Tests\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\Account\Domain\Event\User\UserPasswordChangedEvent;
use Ergonode\Account\Domain\ValueObject\Password;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UserPasswordChangedEventTest extends TestCase
{
    public function testCreateEvent(): void
    {
        /** @var UserId|MockObject $id */
        $id = $this->createMock(UserId::class);
        $password = $this->createMock(Password::class);

        $event = new UserPasswordChangedEvent($id, $password);

        $this->assertEquals($id, $event->getAggregateId());
        $this->assertEquals($password, $event->getPassword());
    }
}
