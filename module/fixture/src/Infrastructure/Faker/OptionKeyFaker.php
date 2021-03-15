<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Faker\Provider\Base as BaseProvider;
use Ramsey\Uuid\Uuid;

/**
 * Class OptionKeyFaker
 */
class OptionKeyFaker extends BaseProvider
{
    /**
     * @throws \Exception
     */
    public function optionKey(?string $key = null): OptionKey
    {
        if (null === $key) {
            $key = Uuid::uuid4()->toString();
        }

        return new OptionKey($key);
    }
}
