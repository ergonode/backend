<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Persistence\Repository\Mapper;

use Ergonode\Account\Domain\Entity\UserResetPasswordToken;
use Ergonode\Account\Domain\ValueObject\ResetToken;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;

class DbalUserResetPasswordTokenMapper
{
    public function map(UserResetPasswordToken $userResetPasswordToken): array
    {
        return [
            'user_id' => $userResetPasswordToken->getUserId(),
            'token' => $userResetPasswordToken->getToken()->getValue(),
            'expires_at' => $userResetPasswordToken->getExpiresAt(),
            'consumed' => $userResetPasswordToken->getConsumed(),
        ];
    }

    public function create(array $record): UserResetPasswordToken
    {
        $consumed = $record['consumed'] ? new \DateTime($record['consumed']) : null;

        return new UserResetPasswordToken(
            new UserId($record['user_id']),
            new ResetToken($record['token']),
            new \DateTime($record['expires_at']),
            $consumed
        );
    }
}
