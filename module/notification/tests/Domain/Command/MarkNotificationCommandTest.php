<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Notification\Tests\Domain\Command;

use Ergonode\Notification\Domain\Command\MarkNotificationCommand;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class MarkNotificationCommandTest extends TestCase
{
    public function testCommandCreation(): void
    {
        $notificationId = $this->createMock(Uuid::class);
        $userId = $this->createMock(UserId::class);
        $readAt = $this->createMock(\DateTime::class);

        $command = new MarkNotificationCommand($notificationId, $userId, $readAt);

        $this->assertSame($notificationId, $command->getNotificationId());
        $this->assertSame($userId, $command->getUserId());
        $this->assertSame($readAt, $command->getReadAt());
    }
}
