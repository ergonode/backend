<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\Core\Domain\ValueObject\Language;
use Faker\Provider\Base as BaseProvider;
use Ramsey\Uuid\Uuid;

/**
 * Class UuidFaker
 */
class UuidFaker extends BaseProvider
{
    /**
     * @param string|null $uuid
     *
     * @return Language
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
