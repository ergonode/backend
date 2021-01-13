<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Notification\Tests\Domain\Command;

use Ergonode\Notification\Domain\Command\MarkAllNotificationsCommand;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;

class MarkAllNotificationsCommandTest extends TestCase
{
    public function testCommandCreation(): void
    {
        $userId = $this->createMock(UserId::class);
        $readAt = $this->createMock(\DateTime::class);

        $command = new MarkAllNotificationsCommand($userId, $readAt);

        self::assertSame($userId, $command->getUserId());
        self::assertSame($readAt, $command->getReadAt());
    }
}
