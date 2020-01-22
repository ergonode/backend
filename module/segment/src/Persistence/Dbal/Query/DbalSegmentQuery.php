<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Condition\Domain\Entity\ConditionSetId;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DbalDataSet;
use Ergonode\Segment\Domain\Query\SegmentQueryInterface;
use Ergonode\Segment\Domain\ValueObject\SegmentCode;

/**
 */
class DbalSegmentQuery implements SegmentQueryInterface
{
    private const TABLE = 'segment';
    private const FIELDS = [
        't.id',
        't.code',
        't.status',
    ];

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
     * {@inheritDoc}
     */
    public function getDataSet(Language $language): DbalDataSet
    {
        $query = $this->getQuery();
        $query->addSelect(sprintf('(name->>\'%s\') AS name', $language->getCode()));
        $query->addSelect(sprintf('(description->>\'%s\') AS description', $language->getCode()));

        $result = $this->connection->createQueryBuilder();
        $result->select('*');
        $result->from(sprintf('(%s)', $query->getSQL()), 't');

        return new DbalDataSet($result);
    }

    /**
     * {@inheritDoc}
     */
    public function findIdByConditionSetId(ConditionSetId $conditionSetId): array
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->select('id')
            ->from(self::TABLE)
            ->where('condition_set_id = :id')
            ->setParameter('id', $conditionSetId->getValue());
        $result = $queryBuilder->execute()->fetchAll(\PDO::FETCH_COLUMN);

        if (false === $result) {
            $result = [];
        }

        foreach ($result as &$item) {
            $item = new ConditionSetId($item);
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function isExistsByCode(SegmentCode $segmentCode): bool
    {
        $queryBuilder = $this->connection->createQueryBuilder()
            ->select('id')
            ->from(self::TABLE)
            ->where('code = :code')
            ->setParameter('code', $segmentCode->getValue())
            ->setMaxResults(1);

        $result = $queryBuilder->execute()->fetchColumn();

        return !empty($result);
    }

    /**
     * @return array
     */
    public function getAllSegmentIds(): array
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

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(self::FIELDS)
            ->from(self::TABLE, 't');
    }
}
