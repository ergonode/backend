<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Account\Domain\Query\LogQueryInterface;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;
use Ergonode\Grid\Filter\FilterBuilderProvider;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;

class DbalLogQuery implements LogQueryInterface
{
    private Connection $connection;

    private FilterBuilderProvider $filterBuilderProvider;

    public function __construct(Connection $connection, FilterBuilderProvider $filterBuilderProvider)
    {
        $this->connection = $connection;
        $this->filterBuilderProvider = $filterBuilderProvider;
    }

    public function getDataSet(?UserId $id = null): DataSetInterface
    {
        $result = $this->connection->createQueryBuilder();

        $qb = $this->getQuery();
        if (null !== $id) {
            $qb->andWhere($qb->expr()->eq('recorded_by', ':id'));
            $result->setParameter(':id', $id->getValue());
        }

        $result->select('*');
        $result->from(sprintf('(%s)', $qb->getSQL()), 't');

        return new DbalDataSet($result, $this->filterBuilderProvider);
    }

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
