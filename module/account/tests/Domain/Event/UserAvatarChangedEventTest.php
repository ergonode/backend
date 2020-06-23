<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Tests\Domain\Event;

use Ergonode\Account\Domain\Event\User\UserAvatarChangedEvent;
use Ergonode\SharedKernel\Domain\Aggregate\AvatarId;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class UserAvatarChangedEventTest extends TestCase
{
    /**
     */
    public function testCreateEvent(): void
    {
        /** @var UserId|MockObject $id */
        $id = $this->createMock(UserId::class);
        $avatarId = $this->createMock(AvatarId::class);

        $event = new UserAvatarChangedEvent($id, $avatarId);

        $this->assertEquals($id, $event->getAggregateId());
        $this->assertEquals($avatarId, $event->getAvatarId());
    }
}
