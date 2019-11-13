<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Notification\Tests\Domain\Entity;

use Ergonode\Account\Domain\Entity\RoleId;
use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Notification\Domain\Command\SendNotificationCommand;
use Ergonode\Notification\Domain\Entity\Notification;
use Ergonode\Notification\Domain\Entity\NotificationId;
use PHPUnit\Framework\TestCase;

class NotificationTest extends TestCase
{
    /**
     * @param NotificationId $id
     * @param \DateTime      $createdAt
     * @param string         $message
     * @param UserId|null    $authorId
     *
     * @dataProvider dataProvider
     */
    public function testEntityCreation(NotificationId $id, \DateTime $createdAt, string $message, UserId $authorId = null): void
    {
        $command = new Notification($id, $createdAt, $message, $authorId);
        $this->assertSame($id, $command->getId());
        $this->assertSame($createdAt, $command->getCreatedAt());
        $this->assertSame($message, $command->getMessage());
        $this->assertSame($authorId, $command->getAuthorId());
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [
                $this->createMock(NotificationId::class),
                $this->createMock(\DateTime::class),
                'Any Message',
                null,
            ],
            [
                $this->createMock(NotificationId::class),
                $this->createMock(\DateTime::class),
                'Any Message',
                $this->createMock(UserId::class),
            ],
        ];
    }
}
