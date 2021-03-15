<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Encoder;

use Ergonode\Account\Domain\Entity\User;
use Ergonode\Account\Domain\ValueObject\Password;

interface UserPasswordEncoderInterface
{
    public function encode(User $user, Password $password): Password;
}
