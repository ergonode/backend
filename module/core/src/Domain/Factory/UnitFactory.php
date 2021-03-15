<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Domain\Factory;

use Ergonode\Core\Domain\Entity\Unit;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;

class UnitFactory
{
    public function create(UnitId $id, string $name, string $symbol): Unit
    {
        return new Unit($id, $name, $symbol);
    }
}
