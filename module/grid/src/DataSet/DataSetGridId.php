<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\DataSet;

use Doctrine\DBAL\FetchMode;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Grid\GridInterface;
use Ergonode\Grid\QueryInterface;

class DataSetGridId
{
    public function getItems(
        GridInterface $grid,
        GridConfigurationInterface $configuration,
        QueryInterface $query
    ): array {
        $queryBuilder = $query->getQueryBuilder(
            $configuration->getFilters(),
            $grid->getColumns()
        );
        $queryBuilder->select('id');

        return $queryBuilder->execute()->fetchAll(FetchMode::COLUMN);
    }
}
