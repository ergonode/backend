<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Tests\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\Account\Domain\Event\User\UserFirstNameChangedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class UserFirstNameChangedEventTest extends TestCase
{
    /**
     */
    public function testCreateEvent(): void
    {
        /** @var UserId|MockObject $id */
        $id = $this->createMock(UserId::class);
        $from = 'Old first Name';
        $to = 'New first Name';

        $event = new UserFirstNameChangedEvent($id, $from, $to);

        $this->assertEquals($id, $event->getAggregateId());
        $this->assertEquals($from, $event->getFrom());
        $this->assertEquals($to, $event->getTo());
    }
}
