<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\SharedKernel\Domain\Aggregate\UnitId;
use Faker\Provider\Base as BaseProvider;

/**
 * Class UnitFaker
 */
class UnitIdFaker extends BaseProvider
{
    public function unitId(string $unit = null): UnitId
    {
        if ($unit) {
            return new UnitId($unit);
        }

        return UnitId::generate();
    }
}
