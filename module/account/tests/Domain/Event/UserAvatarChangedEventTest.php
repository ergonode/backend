<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Tests\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\Account\Domain\Event\User\UserAvatarChangedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UserAvatarChangedEventTest extends TestCase
{
    public function testCreateEvent(): void
    {
        /** @var UserId|MockObject $id */
        $id = $this->createMock(UserId::class);
        $avatarFilename = 'filename.jpg';

        $event = new UserAvatarChangedEvent($id, $avatarFilename);

        $this->assertEquals($id, $event->getAggregateId());
        $this->assertEquals($avatarFilename, $event->getAvatarFilename());
    }
}
