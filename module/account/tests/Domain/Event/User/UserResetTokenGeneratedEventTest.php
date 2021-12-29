<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Tests\Domain\Event\User;

use Ergonode\Account\Domain\Event\User\UserResetTokenGeneratedEvent;
use Ergonode\Account\Domain\ValueObject\ResetToken;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UserResetTokenGeneratedEventTest extends TestCase
{
    /**
     * @var UserId|MockObject
     */
    private UserId $userId;

    /**
     * @var ResetToken|MockObject
     */
    private ResetToken $token;

    private string $path;

    protected function setUp(): void
    {
        $this->userId = $this->createMock(UserId::class);
        $this->token = $this->createMock(ResetToken::class);
        $this->path = 'http://path/';
    }

    public function testCreateEvent(): void
    {
        $event = new UserResetTokenGeneratedEvent($this->userId, $this->token, $this->path);

        self::assertEquals($this->userId, $event->getUserId());
        self::assertEquals($this->token, $event->getToken());
        self::assertEquals($this->path, $event->getUrl());
    }
}
