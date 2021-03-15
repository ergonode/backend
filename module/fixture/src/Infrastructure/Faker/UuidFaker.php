<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Faker\Provider\Base as BaseProvider;
use Ramsey\Uuid\Uuid;

class UuidFaker extends BaseProvider
{
    /**
     * @param mixed $uuid
     *
     *
     * @throws \Exception
     */
    public function uuid($uuid = null): string
    {
        if (null === $uuid) {
            $uuid = Uuid::uuid4()->toString();
        }

        $uuid = (string) $uuid;

        if (!Uuid::isValid($uuid)) {
            throw new \InvalidArgumentException(\sprintf('Incorrect uuid %s', $uuid));
        }

        return $uuid;
    }
}
