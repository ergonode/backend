<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\SharedKernel\Domain\Aggregate\UnitId;
use Faker\Provider\Base as BaseProvider;

/**
 * Class UnitFaker
 */
class UnitIdFaker extends BaseProvider
{
    /**
     * @param string|null $unit
     *
     * @return UnitId
     */
    public function unitId(string $unit = null): UnitId
    {
        if (null === $unit) {
            $units = ['s', 'Ω', 'A', 'K', 'C', 'J', 'Gy', 'sr', 'cd', 'rad'];
            $random = array_rand($units);
            $unit = $units[$random];
        }

        return new UnitId($unit);
    }
}
