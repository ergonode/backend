<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\SharedKernel\Domain\AbstractId;
use Faker\Provider\Base as BaseProvider;
use Ergonode\Value\Domain\ValueObject\StringCollectionValue;

/**
 */
class StringCollectionValueFaker extends BaseProvider
{
    /**
     * @param mixed $value
     *
     * @return StringCollectionValue
     */
    public function stringCollectionValue($value): StringCollectionValue
    {
        if ($value instanceof AbstractId) {
            return new StringCollectionValue([$value->getValue()]);
        }

        return new StringCollectionValue([(string) $value]);
    }
}
