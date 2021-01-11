<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Designer\Domain\Query\TemplateGroupQueryInterface;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\Factory\DbalDataSetFactory;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateGroupId;

class DbalTemplateGroupQuery implements TemplateGroupQueryInterface
{
    private const TABLE = 'designer.template_group';
    private const FIELDS = [
        'id',
        'name',
        'custom',
    ];

    private Connection $connection;

    private DbalDataSetFactory $dataSetFactory;

    public function __construct(Connection $connection, DbalDataSetFactory $dataSetFactory)
    {
        $this->connection = $connection;
        $this->dataSetFactory = $dataSetFactory;
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


    public function getDataSet(): DataSetInterface
    {
        return $this->dataSetFactory->create($this->getQuery());
    }

    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(self::FIELDS)
            ->from(self::TABLE);
    }
}
