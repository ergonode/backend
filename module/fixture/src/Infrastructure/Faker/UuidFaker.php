<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Faker\Provider\Base as BaseProvider;
use Ramsey\Uuid\Uuid;

/**
 */
class UuidFaker extends BaseProvider
{
    /**
     * @param string|null $uuid
     *
     * @return string
     *
     * @throws \Exception
     */
    public function uuid(string $uuid = null): string
    {
        if (null === $uuid) {
            $uuid = Uuid::uuid4()->toString();
        }

        if (!Uuid::isValid($uuid)) {
            throw new \InvalidArgumentException(\sprintf('Incorrect uuid %s', $uuid));
        }

        return $uuid;
    }
}
