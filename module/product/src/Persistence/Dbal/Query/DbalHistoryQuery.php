<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\Query\HistoryQueryInterface;

/**
 */
class DbalHistoryQuery implements HistoryQueryInterface
{
    /**
     * @var  Connection
     */
    private Connection $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param ProductId $id
     *
     * @return DataSetInterface
     */
    public function getDataSet(ProductId $id): DataSetInterface
    {
        $result = $this->connection->createQueryBuilder();
        $qb = $this->getQuery();
        $qb->andWhere($qb->expr()->eq('es.aggregate_id', ':id'));
        $result->setParameter(':id', $id->getValue());
        $result->select('*');
        $result->from(sprintf('(%s)', $qb->getSQL()), 't');

        return new DbalDataSet($result);
    }

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('es.id, es.payload, es.recorded_at, es.recorded_by AS author_id')
            ->addSelect('coalesce(u.first_name || \' \' || u.last_name, \'System\') AS author')
            ->addSelect('ese.translation_key as event')
            ->from('public.event_store', 'es')
            ->join('es', 'public.event_store_event', 'ese', 'es.event_id = ese.id')
            ->leftJoin('es', 'public.users', 'u', 'u.id = es.recorded_by');
    }
}
