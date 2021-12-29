<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Persistence\DataSet;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Grid\ColumnInterface;
use Ergonode\Grid\DbalDataSetQueryInterface;
use Ergonode\Grid\Request\FilterValueCollection;

class DbalQueryBuilderProductDataSet extends DbalProductDataSet implements DbalDataSetQueryInterface
{
    /**
     * @param ColumnInterface[] $columns
     */
    public function getQueryBuilder(FilterValueCollection $values, array $columns = []): QueryBuilder
    {
        $query = $this->build($columns);

        $qb = clone $this->queryBuilder;
        $qb->select('*');
        $qb->from(sprintf('(%s)', $query->getSQL()), 't');

        $this->buildFilters($qb, $values, $columns);

        return $qb;
    }
}
