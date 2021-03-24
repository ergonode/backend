<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\SharedKernel\Domain\AbstractId;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Faker\Provider\Base as BaseProvider;

class StringValueFaker extends BaseProvider
{
    /**
     * @param mixed $value
     */
    public function stringValue($value): StringValue
    {
        if ($value instanceof AbstractId) {
            return new StringValue($value->getValue());
        }

        return new StringValue((string) $value);
    }
}
