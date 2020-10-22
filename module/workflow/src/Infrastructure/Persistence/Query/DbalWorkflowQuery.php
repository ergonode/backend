<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\Workflow\Domain\Query\WorkflowQueryInterface;

class DbalWorkflowQuery implements WorkflowQueryInterface
{
    private const TABLE = 'workflow';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return WorkflowId[]
     */
    public function getWorkflowIdsWithDefaultStatus(StatusId $id): array
    {
        $query = $this->getQuery();
        $query->where($query->expr()->eq('a.default_status', ':statusId'));
        $query->setParameter(':statusId', $id);

        $result = $query->execute()->fetchAll(\PDO::FETCH_COLUMN);

        foreach ($result as &$item) {
            $item = new WorkflowId($item);
        }

        return $result;
    }

    public function findWorkflowIdByCode(string $code): ?WorkflowId
    {
        $query = $this->getQuery();
        $result = $query
            ->where($query->expr()->eq('a.code', ':code'))
            ->setParameter(':code', $code)
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);

        if ($result) {
            return new WorkflowId($result);
        }

        return null;
    }

    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('id')
            ->from(self::TABLE, 'a');
    }
}
