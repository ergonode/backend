<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Domain\Query;

use Ergonode\Grid\DataSetInterface;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;

interface UnitQueryInterface
{
    public function getDataSet(): DataSetInterface;

    /**
     * @return array
     */
    public function getAllUnitIds(): array;

    public function findIdByCode(string $code): ?UnitId;

    public function findCodeById(UnitId $unitId): ?string;

    public function findIdByName(string $name): ?UnitId;
}
