<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Validator;

use Ergonode\Account\Domain\Repository\UserResetPasswordTokenRepositoryInterface;
use Ergonode\Account\Domain\ValueObject\ResetToken;

class TokenValidator
{
    private UserResetPasswordTokenRepositoryInterface $repository;

    public function __construct(UserResetPasswordTokenRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function validate(string $token): bool
    {
        if (!ResetToken::isValid($token)) {
            return false;
        }
        $userToken = $this->repository->load(new ResetToken($token));
        if (!$userToken) {
            return false;
        }
        $now = new \DateTime();

        return !(null !== $userToken->getConsumed()
            || $userToken->getExpiresAt() <= $now);
    }
}
