<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Repository;

use Ergonode\Account\Domain\Entity\UserResetPasswordToken;
use Ergonode\Account\Domain\ValueObject\ResetToken;

interface UserResetPasswordTokenRepositoryInterface
{
    public function load(ResetToken $token): ?UserResetPasswordToken;

    public function save(UserResetPasswordToken $userResetPasswordToken): void;
}
