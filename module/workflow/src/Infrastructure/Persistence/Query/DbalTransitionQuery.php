<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;
use Ergonode\Grid\Filter\FilterBuilderProvider;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\Workflow\Domain\Query\TransitionQueryInterface;

class DbalTransitionQuery implements TransitionQueryInterface
{
    private const TABLE = 'workflow_transition';

    private Connection $connection;

    private FilterBuilderProvider $filterBuilderProvider;

    public function __construct(Connection $connection, FilterBuilderProvider $filterBuilderProvider)
    {
        $this->connection = $connection;
        $this->filterBuilderProvider = $filterBuilderProvider;
    }

    public function getDataSet(WorkflowId $workflowId, Language $language): DataSetInterface
    {
        $query = $this->getQuery($language);
        $query->andWhere($query->expr()->eq('workflow_id', ':workflowId'));

        $result = $this->connection->createQueryBuilder();
        $result->select('*');
        $result->from(sprintf('(%s)', $query->getSQL()), 't');
        $result->setParameter(':workflowId', $workflowId->getValue());

        return new DbalDataSet($result, $this->filterBuilderProvider);
    }

    public function hasStatus(WorkflowId $workflowId, StatusId $statusId): bool
    {
        $query = $this->connection->createQueryBuilder();
        $query->select('*');
        $query->from(self::TABLE);

        $query->andWhere($query->expr()->eq('workflow_id', ':workflowId'));
        $query->andWhere(
            $query->expr()->orX(
                $query->expr()->eq('source_id', ':statusId'),
                $query->expr()->eq('destination_id', ':statusId')
            )
        );
        $query->setParameter(':workflowId', $workflowId->getValue());
        $query->setParameter(':statusId', $statusId->getValue());

        $result = $query->execute()->fetchAll(\PDO::FETCH_COLUMN);

        if ($result) {
             return true;
        }

        return false;
    }

    private function getQuery(Language $language): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(sprintf(
                'ss.id as source,'.
                'ds.id as destination, t.name->>\'%s\' as name, t.description->>\'%s\' as description',
                $language->getCode(),
                $language->getCode()
            ))
            ->join('t', 'status', 'ss', 'ss.id = t.source_id')
            ->join('t', 'status', 'ds', 'ds.id = t.destination_id')
            ->from(self::TABLE, 't');
    }
}
