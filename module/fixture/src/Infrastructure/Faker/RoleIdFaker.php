<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Faker\Provider\Base as BaseProvider;
use Ramsey\Uuid\Uuid;

/**
 */
class RoleIdFaker extends BaseProvider
{
    private const NAMESPACE = '6601b60b-1701-4db4-87da-944c03aae69f';

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
            return new RoleId(Uuid::uuid5(self::NAMESPACE, $name)->toString());
        }

        return RoleId::generate();
    }
}
