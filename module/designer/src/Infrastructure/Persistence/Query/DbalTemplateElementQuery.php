<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Designer\Domain\Query\TemplateElementQueryInterface;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;
use Ergonode\Grid\Filter\FilterBuilderProvider;

class DbalTemplateElementQuery implements TemplateElementQueryInterface
{
    private const TABLE = 'designer.element_type';

    private Connection $connection;

    private FilterBuilderProvider $filterBuilderProvider;

    public function __construct(Connection $connection, FilterBuilderProvider $filterBuilderProvider)
    {
        $this->connection = $connection;
        $this->filterBuilderProvider = $filterBuilderProvider;
    }

    public function getDataSet(): DataSetInterface
    {
        return new DbalDataSet($this->getQuery(), $this->filterBuilderProvider);
    }

    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('*')
            ->from(self::TABLE, 'e');
    }
}
