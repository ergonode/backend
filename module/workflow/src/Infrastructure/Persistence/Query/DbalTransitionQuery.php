<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\SharedKernel\Domain\Aggregate\TransitionId;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\Workflow\Domain\Query\TransitionConditionSetQueryInterface;
use Ergonode\Workflow\Domain\Query\TransitionQueryInterface;

class DbalTransitionQuery implements TransitionQueryInterface, TransitionConditionSetQueryInterface
{
    private const TABLE = 'workflow_transition';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
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

    /**
     * @return WorkflowId[]
     */
    public function findWorkflowsByStatus(StatusId $statusId): array
    {
        $query = $this->connection->createQueryBuilder();
        $query->select('workflow_id');
        $query->from(self::TABLE);
        $query->where(
            $query->expr()->or(
                $query->expr()->eq('source_id', ':statusId'),
                $query->expr()->eq('destination_id', ':statusId')
            )
        );
        $query->setParameter(':statusId', $statusId->getValue());

        $result = [];
        $records = $query->execute()->fetchAll(\PDO::FETCH_COLUMN);

        foreach ($records as $record) {
            $result[] = new WorkflowId($record);
        }

        return $result;
    }

    public function findIdByConditionSetId(ConditionSetId $conditionSetId): array
    {
        $query = $this->connection->createQueryBuilder();
        $query->select('transition_id')
            ->from(self::TABLE)
            ->andWhere($query->expr()->eq('condition_set_id', ':conditionSetId'))
            ->setParameter(':conditionSetId', $conditionSetId->getValue());

        $result = $query->execute()->fetchFirstColumn();

        return array_map(
            static fn (string $item) => new TransitionId($item),
            $result,
        );
    }
}
