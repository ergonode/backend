<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\AttributeDate\Domain\ValueObject\DateFormat;
use Faker\Provider\Base as BaseProvider;

/**
 * Class AttributeDateFormatProvider
 */
class AttributeDateFormatFaker extends BaseProvider
{
    /**
     * @param string|null $format
     *
     * @return DateFormat
     *
     */
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
