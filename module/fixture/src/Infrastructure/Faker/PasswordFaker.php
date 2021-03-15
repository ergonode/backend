<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\Account\Domain\ValueObject\Password;

class PasswordFaker
{
    /**
     * @throws \Exception
     */
    public function password(string $password): Password
    {
        return new Password($password);
    }
}
