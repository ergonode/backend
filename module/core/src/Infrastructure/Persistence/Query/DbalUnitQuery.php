<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\Query\UnitQueryInterface;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\Factory\DbalDataSetFactory;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;

class DbalUnitQuery implements UnitQueryInterface
{
    private const TABLE = 'unit';
    private const FIELDS = [
        'id',
        'name',
        'symbol',
    ];

    private Connection $connection;

    private DbalDataSetFactory $dataSetFactory;

    public function __construct(Connection $connection, DbalDataSetFactory $dataSetFactory)
    {
        $this->connection = $connection;
        $this->dataSetFactory = $dataSetFactory;
    }

    public function getDataSet(): DataSetInterface
    {
        $qb = $this->getQuery();

        $result = $this->connection->createQueryBuilder();
        $result->select('*');
        $result->from(sprintf('(%s)', $qb->getSQL()), 't');

        return $this->dataSetFactory->create($result);
    }

    /**
     * @return array
     */
    public function getAllUnitIds(): array
    {
        $query = $this->getQuery();
        $result = $query->select('id')
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);

        if ($result) {
            return $result;
        }

        return [];
    }

    public function findIdByCode(string $code): ?UnitId
    {
        $qb = $this->getQuery();
        $result = $qb->select('id')
            ->where($qb->expr()->eq('symbol', ':symbol'))
            ->setParameter(':symbol', $code)
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);

        if ($result) {
            return new UnitId($result);
        }

        return null;
    }

    public function findCodeById(UnitId $unitId): ?string
    {
        $qb = $this->getQuery();

        $result = $qb->select('symbol')
            ->where($qb->expr()->eq('id', ':id'))
            ->setParameter(':id', $unitId->getValue())
            ->setMaxResults(1)
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);

        if ($result) {
            return $result;
        }

        return null;
    }

    public function findIdByName(string $name): ?UnitId
    {
        $qb = $this->getQuery();
        $result = $qb->select('id')
            ->where($qb->expr()->eq('name', ':name'))
            ->setParameter(':name', $name)
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);

        if ($result) {
            return new UnitId($result);
        }

        return null;
    }

    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(self::FIELDS)
            ->from(self::TABLE);
    }
}
