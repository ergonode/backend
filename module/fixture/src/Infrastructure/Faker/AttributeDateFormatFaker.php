<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\Attribute\Domain\ValueObject\DateFormat;
use Faker\Provider\Base as BaseProvider;

class AttributeDateFormatFaker extends BaseProvider
{
    public function dateFormat(string $format = null): DateFormat
    {
        if (null === $format) {
            $formats = DateFormat::AVAILABLE;
            $random = array_rand($formats);
            $format = $formats[$random];
        }

        return new DateFormat($format);
    }
}
