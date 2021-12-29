<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\Workflow\Domain\Query\TransitionGridQueryInterface;

class DbalTransitionGridQuery implements TransitionGridQueryInterface
{
    private const TABLE = 'workflow_transition';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getDataSet(WorkflowId $workflowId, Language $language): QueryBuilder
    {
        $query = $this->getQuery($language);
        $query->andWhere($query->expr()->eq('workflow_id', ':qb_workflowId'));
        $query->setParameter(':qb_workflowId', $workflowId->getValue());

        return $query;
    }

    private function getQuery(Language $language): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(
                'ss.id as source, ds.id as destination'
            )
            ->addSelect('roles, condition_set_id')
            ->join('t', 'status', 'ss', 'ss.id = t.source_id')
            ->join('t', 'status', 'ds', 'ds.id = t.destination_id')
            ->from(self::TABLE, 't');
    }
}
