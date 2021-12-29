<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Grid\Request\FilterValueCollection;

interface DbalDataSetQueryInterface
{
    /**
     * @param ColumnInterface[] $columns
     */
    public function getQueryBuilder(FilterValueCollection $values, array $columns = []): QueryBuilder;
}
