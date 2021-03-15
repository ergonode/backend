<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Tests\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\Account\Domain\Event\User\UserLastNameChangedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UserLastNameChangedEventTest extends TestCase
{
    public function testCreateEvent(): void
    {
        /** @var UserId|MockObject $id */
        $id = $this->createMock(UserId::class);
        $to = 'New last Name';

        $event = new UserLastNameChangedEvent($id, $to);

        $this->assertEquals($id, $event->getAggregateId());
        $this->assertEquals($to, $event->getTo());
    }
}
