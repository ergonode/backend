<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Account\Domain\Query\LogQueryInterface;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;

/**
 */
class DbalLogQuery implements LogQueryInterface
{
    /**
     * @var  Connection
     */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param UserId|null $id
     *
     * @return DataSetInterface
     */
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

        return new DbalDataSet($result);
    }

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('es.id, event, payload, recorded_at, recorded_by AS author_id, coalesce(u.first_name || \' \' || u.last_name, \'System\') AS author')
            ->leftJoin('es', 'users', 'u', 'u.id = es.recorded_by')
            ->from('event_store', 'es');
    }
}
