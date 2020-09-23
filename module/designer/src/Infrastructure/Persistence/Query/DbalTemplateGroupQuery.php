<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Designer\Domain\Query\TemplateGroupQueryInterface;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateGroupId;

/**
 */
class DbalTemplateGroupQuery implements TemplateGroupQueryInterface
{
    private const TABLE = 'designer.template_group';
    private const FIELDS = [
        'id',
        'name',
        'custom',
    ];

    /**
     * @var Connection
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
     * @return array
     */
    public function getDictionary(): array
    {
        return $this->getQuery()
            ->execute()
            ->fetchAll();
    }

    /**
     * @return TemplateGroupId
     */
    public function getDefaultId(): TemplateGroupId
    {
        $qb = $this->getQuery();
        $result = $qb
            ->select('id')
            ->where($qb->expr()->eq('custom', ':custom'))
            ->setParameter(':custom', false, \PDO::PARAM_BOOL)
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);

        return new TemplateGroupId($result);
    }


    /**
     * @return DataSetInterface
     */
    public function getDataSet(): DataSetInterface
    {
        return new DbalDataSet($this->getQuery());
    }

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(self::FIELDS)
            ->from(self::TABLE);
    }
}
