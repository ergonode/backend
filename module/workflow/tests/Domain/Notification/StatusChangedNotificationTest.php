<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Notification;

use Ergonode\Workflow\Domain\Notification\StatusChangedNotification;
use PHPUnit\Framework\TestCase;
use Ergonode\Product\Domain\ValueObject\Sku;
use PHPUnit\Framework\MockObject\MockObject;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use Ergonode\Account\Domain\Entity\User;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;

/**
 */
class StatusChangedNotificationTest extends TestCase
{
    /**
     * @var Sku|MockObject
     */
    private Sku $sku;

    /**
     * @var StatusCode|MockObject
     */
    private StatusCode $from;

    /**
     * @var StatusCode|MockObject
     */
    private StatusCode $to;

    /**
     * @var User|MockObject
     */
    private User $user;

    /**
     */
    protected function setUp(): void
    {
        $this->sku = $this->createMock(Sku::class);
        $this->from = $this->createMock(StatusCode::class);
        $this->to = $this->createMock(StatusCode::class);
        $this->user = $this->createMock(User::class);
    }

    /**
     * @throws \Exception
     */
    public function testCreation(): void
    {
        $userId = $this->createMock(UserId::class);
        $this->user->method('getId')->willReturn($userId);
        $notification = new StatusChangedNotification($this->sku, $this->from, $this->to, $this->user);
        self::assertEquals($userId, $notification->getAuthorId());
        self::assertEquals($userId, $notification->getUserId());
        self::assertNotEmpty($notification->getCreatedAt());
    }

    /**
     * @throws \Exception
     */
    public function testReturnedParameters(): void
    {
        $this->sku->method('getValue')->willReturn('sku value');
        $this->from->method('getValue')->willReturn('code value from');
        $this->to->method('getValue')->willReturn('code value to');
        $this->user->method('getFirstName')->willReturn('first name');
        $this->user->method('getLastName')->willReturn('last name');

        $notification = new StatusChangedNotification($this->sku, $this->from, $this->to, $this->user);
        $parameters = $notification->getParameters();
        self::assertSame('sku value', $parameters['%sku%']);
        self::assertSame('code value from', $parameters['%from%']);
        self::assertSame('code value to', $parameters['%to%']);
        self::assertSame('first name last name', $parameters['%user%']);
    }

    /**
     * @throws \Exception
     */
    public function testReturnedMessage(): void
    {
        $notification = new StatusChangedNotification($this->sku, $this->from, $this->to, $this->user);
        self::assertSame(
            'Product "%sku%" status was changed from "%from%" to "%to%" by user "%user%"',
            $notification->getMessage()
        );
    }
}
