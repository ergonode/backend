<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Provider;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Grid\GridInterface;
use Ergonode\Grid\DbalDataSetQueryInterface;

class ProductIdsProvider
{
    public function getProductIds(
        GridInterface $grid,
        GridConfigurationInterface $configuration,
        DbalDataSetQueryInterface $query
    ): QueryBuilder {
        $queryBuilder = $query->getQueryBuilder(
            $configuration->getFilters(),
            $grid->getColumns()
        );
        $queryBuilder->select('id');

        return $queryBuilder;
    }
}
