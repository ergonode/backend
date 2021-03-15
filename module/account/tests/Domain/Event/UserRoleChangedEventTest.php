<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Tests\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\Account\Domain\Event\User\UserRoleChangedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UserRoleChangedEventTest extends TestCase
{
    public function testCreateEvent(): void
    {
        /** @var UserId|MockObject $id */
        $id = $this->createMock(UserId::class);
        $to = $this->createMock(RoleId::class);

        $event = new UserRoleChangedEvent($id, $to);

        $this->assertEquals($id, $event->getAggregateId());
        $this->assertEquals($to, $event->getTo());
    }
}
