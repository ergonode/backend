<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Tests\Domain\Notification;

use Ergonode\BatchAction\Domain\Notification\BatchActionEndedNotification;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use PHPUnit\Framework\TestCase;
use Ergonode\BatchAction\Domain\Entity\BatchAction;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;

class BatchActionEndedNotificationTest extends TestCase
{

    private UserId $userId;

    private BatchAction $batchAction;

    protected function setUp(): void
    {
        $this->batchAction = $this->createMock(BatchAction::class);
        $this->userId = $this->createMock(UserId::class);
    }

    /**
     * @throws \Exception
     */
    public function testCreation(): void
    {
        $notification = new BatchActionEndedNotification($this->batchAction, $this->userId);
        self::assertEquals($this->userId, $notification->getAuthorId());
        self::assertNotEmpty($notification->getCreatedAt());
    }

    /**
     * @throws \Exception
     */
    public function testReturnedParameters(): void
    {
        $this->batchAction->method('getType')->willReturn(new BatchActionType('test'));
        $notification = new BatchActionEndedNotification($this->batchAction, $this->userId);
        $parameters = $notification->getParameters();
        self::assertSame('test', $parameters['%type%']);
    }

    /**
     * @throws \Exception
     */
    public function testReturnedMessage(): void
    {
        $notification = new BatchActionEndedNotification($this->batchAction, $this->userId);
        self::assertSame(
            'Batch action "%type%" ended',
            $notification->getMessage()
        );
    }
}
