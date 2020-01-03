<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\Account\Domain\Entity\RoleId;
use Faker\Provider\Base as BaseProvider;

/**
 */
class RoleIdFaker extends BaseProvider
{
    /**
     * @param string|null $name
     *
     * @return RoleId
     *
     * @throws \Exception
     */
    public function roleId(?string $name = null): RoleId
    {
        if ($name) {
            return RoleId::fromString($name);
        }

        return RoleId::generate();
    }
}
