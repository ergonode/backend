<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\AttributeUnit\Domain\ValueObject\Unit;
use Faker\Provider\Base as BaseProvider;

/**
 * Class UnitFaker
 */
class UnitFaker extends BaseProvider
{
    /**
     * @param string|null $unit
     *
     * @return Unit
     */
    public function unit(string $unit = null): Unit
    {
        if (null === $unit) {
            $units = ['s', 'Ω', 'A', 'K', 'C', 'J', 'Gy', 'sr', 'cd', 'rad'];
            $random = array_rand($units);
            $unit = $units[$random];
        }

        return new Unit($unit);
    }
}
