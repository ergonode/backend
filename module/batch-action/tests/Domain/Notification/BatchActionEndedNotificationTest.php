<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Tests\Domain\Notification;

use Ergonode\BatchAction\Domain\Notification\BatchActionEndedNotification;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use PHPUnit\Framework\TestCase;

class BatchActionEndedNotificationTest extends TestCase
{

    private UserId $userId;

    private string $type;

    protected function setUp(): void
    {
        $this->type = 'test';
        $this->userId = $this->createMock(UserId::class);
    }

    /**
     * @throws \Exception
     */
    public function testCreation(): void
    {
        $notification = new BatchActionEndedNotification($this->type, $this->userId);
        self::assertEquals($this->userId, $notification->getAuthorId());
        self::assertNotEmpty($notification->getCreatedAt());
    }

    /**
     * @throws \Exception
     */
    public function testReturnedParameters(): void
    {
        $notification = new BatchActionEndedNotification($this->type, $this->userId);
        $parameters = $notification->getParameters();
        self::assertSame('test', $parameters['%type%']);
    }

    /**
     * @throws \Exception
     */
    public function testReturnedMessage(): void
    {
        $notification = new BatchActionEndedNotification($this->type, $this->userId);
        self::assertSame(
            'Batch action "%type%" ended',
            $notification->getMessage()
        );
    }
}
