<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Account\Domain\ValueObject\Email;
use Faker\Provider\Base as BaseProvider;

/**
 */
class UserIdFaker extends BaseProvider
{
    /**
     * @param string|null $email
     *
     * @return UserId
     *
     * @throws \Exception
     */
    public function userId(?string $email = null): UserId
    {
        if ($email) {
            return UserId::fromEmail(new Email($email));
        }

        return UserId::generate();
    }
}
