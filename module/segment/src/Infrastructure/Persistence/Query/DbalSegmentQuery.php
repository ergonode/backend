<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Segment\Domain\Query\SegmentQueryInterface;
use Ergonode\Segment\Domain\ValueObject\SegmentCode;
use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;

class DbalSegmentQuery implements SegmentQueryInterface
{
    private const TABLE = 'segment';
    private const FIELDS = [
        't.id',
        't.code',
    ];

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
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
            ->setParameter(':id', $conditionSetId->getValue());
        $result = $queryBuilder->execute()->fetchAll(\PDO::FETCH_COLUMN);

        if (false === $result) {
            $result = [];
        }

        foreach ($result as &$item) {
            $item = new SegmentId($item);
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
     * @return array
     */
    public function getDictionary(): array
    {
        return $this->getQuery()
            ->select('id, code')
            ->execute()
            ->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    /**
     * @return array
     */
    public function autocomplete(
        Language $language,
        string $search = null,
        int $limit = null,
        string $field = null,
        ?string $order = 'ASC'
    ): array {
        $query = $this->connection->createQueryBuilder()
            ->select('id, code, COALESCE(name->>:language, null) as label')
            ->from(self::TABLE, 's')
            ->setParameter(':language', $language->getCode());

        if ($search) {
            $query->orWhere('code ILIKE :search');
            $query->setParameter(':search', '%'.$search.'%');
        }
        if ($field) {
            $query->orderBy($field, $order);
        }

        if ($limit) {
            $query->setMaxResults($limit);
        }

        return $query
            ->execute()
            ->fetchAll();
    }

    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(self::FIELDS)
            ->from(self::TABLE, 't');
    }
}
