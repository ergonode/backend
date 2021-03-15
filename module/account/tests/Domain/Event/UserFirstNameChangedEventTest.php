<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Tests\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\Account\Domain\Event\User\UserFirstNameChangedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UserFirstNameChangedEventTest extends TestCase
{
    public function testCreateEvent(): void
    {
        /** @var UserId|MockObject $id */
        $id = $this->createMock(UserId::class);
        $to = 'New first Name';

        $event = new UserFirstNameChangedEvent($id, $to);

        $this->assertEquals($id, $event->getAggregateId());
        $this->assertEquals($to, $event->getTo());
    }
}
