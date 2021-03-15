<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Tests\Domain\Event\User;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\Account\Domain\Event\User\UserDeactivatedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UserDeactivatedEventTest extends TestCase
{
    public function testEventCreation(): void
    {
        /** @var UserId | MockObject $id */
        $id = $this->createMock(UserId::class);

        $event = new UserDeactivatedEvent($id);

        $this->assertSame($id, $event->getAggregateId());
    }
}
