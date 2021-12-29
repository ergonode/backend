<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Tests\Domain\Entity;

use Ergonode\Account\Domain\Entity\UserResetPasswordToken;
use Ergonode\Account\Domain\ValueObject\ResetToken;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UserResetPasswordTokenTest extends TestCase
{
    /**
     * @var UserId|MockObject
     */
    private UserId $userId;

    /**
     * @var ResetToken|MockObject
     */
    private ResetToken $token;

    /**
     * @var \DateTime|MockObject
     */
    private \DateTime $expiresAt;

    /**
     * @var \DateTime|MockObject
     */
    private \DateTime $consumed;

    protected function setUp(): void
    {
        $this->userId = $this->createMock(UserId::class);
        $this->token = $this->createMock(ResetToken::class);
        $this->expiresAt = $this->createMock(\DateTime::class);
        $this->consumed = $this->createMock(\DateTime::class);
    }

    public function testCreateEntity(): void
    {
        $entity = new UserResetPasswordToken(
            $this->userId,
            $this->token,
            $this->expiresAt,
            $this->consumed
        );

        self::assertEquals($this->userId, $entity->getUserId());
        self::assertEquals($this->token, $entity->getToken());
        self::assertEquals($this->expiresAt, $entity->getExpiresAt());
        self::assertEquals($this->consumed, $entity->getConsumed());
    }

    public function testSetEntity(): void
    {
        $entity = new UserResetPasswordToken(
            $this->userId,
            $this->token,
            $this->expiresAt,
            $this->consumed
        );

        $consumed = new \DateTime();
        $entity->setConsumed($consumed);

        self::assertEquals($consumed, $entity->getConsumed());
    }
}
