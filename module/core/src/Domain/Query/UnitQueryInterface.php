<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Domain\Query;

use Ergonode\Grid\DataSetInterface;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;

/**
 */
interface UnitQueryInterface
{
    /**
     * @return DataSetInterface
     */
    public function getDataSet(): DataSetInterface;

    /**
     * @return array
     */
    public function getAllUnitIds(): array;

    /**
     * @param string $code
     *
     * @return UnitId|null
     */
    public function findIdByCode(string $code): ?UnitId;

    /**
     * @param string $name
     *
     * @return UnitId|null
     */
    public function findIdByName(string $name): ?UnitId;
}
