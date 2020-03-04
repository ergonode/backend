<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Notification\Tests\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\Notification\Domain\Command\SendNotificationCommand;
use Ergonode\Notification\Domain\NotificationInterface;
use PHPUnit\Framework\TestCase;

/**
 */
class SendNotificationCommandTest extends TestCase
{
    /**
     * @param NotificationInterface $notification
     * @param array                 $recipients
     *
     * @dataProvider dataProvider
     */
    public function testCommandCreation(NotificationInterface $notification, array $recipients): void
    {
        $command = new SendNotificationCommand($notification, $recipients);
        $this->assertSame($notification, $command->getNotification());
        $this->assertSame($recipients, $command->getRecipients());
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [
                $this->createMock(NotificationInterface::class),
                [$this->createMock(RoleId::class)],
            ],
            [
                $this->createMock(NotificationInterface::class),
                [$this->createMock(RoleId::class)],
            ],
        ];
    }
}
