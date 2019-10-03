<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;
use Ergonode\Workflow\Domain\Entity\WorkflowId;
use Ergonode\Workflow\Domain\Query\TransitionQueryInterface;

/**
 */
class DbalTransitionQuery implements TransitionQueryInterface
{
    private const TABLE = 'workflow_transition';

    /**
     * @var Connection
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
     * @param WorkflowId $workflowId
     * @param Language   $language
     *
     * @return DataSetInterface
     */
    public function getDataSet(WorkflowId $workflowId, Language $language): DataSetInterface
    {
        $query = $this->getQuery($language);
        $query->andWhere($query->expr()->eq('workflow_id', ':workflowId'));

        $result = $this->connection->createQueryBuilder();
        $result->select('*');
        $result->from(sprintf('(%s)', $query->getSQL()), 't');
        $result->setParameter(':workflowId', $workflowId->getValue());

        return new DbalDataSet($result);
    }

    /**
     * @param Language $language
     *
     * @return QueryBuilder
     */
    private function getQuery(Language $language): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(sprintf('ss.code AS source, ss.id as source_id, ds.code AS destination, ds.id as destination_id, t.name->>\'%s\' as name, t.description->>\'%s\' as description', $language->getCode(), $language->getCode()))
            ->join('t', 'status', 'ss', 'ss.id = t.source_id')
            ->join('t', 'status', 'ds', 'ds.id = t.destination_id')
            ->from(self::TABLE, 't');
    }
}
