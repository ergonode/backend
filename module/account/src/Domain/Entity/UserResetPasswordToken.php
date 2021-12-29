<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Entity;

use Ergonode\Account\Domain\ValueObject\ResetToken;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;

class UserResetPasswordToken
{
    private UserId $userId;

    private ResetToken $token;

    private \DateTime  $expiresAt;

    private ?\DateTime $consumed;

    public function __construct(UserId $userId, ResetToken $token, \DateTime $expiresAt, ?\DateTime $consumed = null)
    {
        $this->userId = $userId;
        $this->token = $token;
        $this->expiresAt = $expiresAt;
        $this->consumed = $consumed;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getToken(): ResetToken
    {
        return $this->token;
    }

    public function getExpiresAt(): \DateTime
    {
        return $this->expiresAt;
    }

    public function getConsumed(): ?\DateTime
    {
        return $this->consumed;
    }

    public function setConsumed(\DateTime $consumed): void
    {
        $this->consumed = $consumed;
    }
}
