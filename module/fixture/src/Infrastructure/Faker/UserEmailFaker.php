<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\SharedKernel\Domain\ValueObject\Email;
use Faker\Provider\Base as BaseProvider;

/**
 */
class UserEmailFaker extends BaseProvider
{
    /**
     * @param string $email
     *
     * @return Email
     *
     * @throws \Exception
     */
    public function userEmail(string $email): Email
    {
        return new Email($email);
    }
}
