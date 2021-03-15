<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Account\Domain\Query\LogGridQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;

class DbalLogGridQuery implements LogGridQueryInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getDataSet(?UserId $id = null): QueryBuilder
    {
        $result = $this->getQuery();

        if ($id) {
            $result
                ->andWhere($result->expr()->eq('recorded_by', ':qb_id'))
                ->setParameter(':qb_id', $id->getValue());
        }

        return $result;
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
