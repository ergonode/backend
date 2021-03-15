<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ProductCollection\Domain\Query\ProductCollectionTypeQueryInterface;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionTypeCode;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;

class DbalProductCollectionTypeQuery implements ProductCollectionTypeQueryInterface
{
    private const PRODUCT_COLLECTION_TYPE_TABLE = 'product_collection_type';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return string[]
     */
    public function getDictionary(): array
    {
        $query = $this->getQuery();

        return $query
            ->select('id', 'code')
            ->execute()
            ->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    public function findIdByCode(ProductCollectionTypeCode $code): ?ProductCollectionTypeId
    {
        $qb = $this->getQuery();
        $result = $qb->select('id')
            ->where($qb->expr()->eq('code', ':code'))
            ->setParameter(':code', $code->getValue())
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);

        if ($result) {
            return new ProductCollectionTypeId($result);
        }

        return null;
    }

    /**
     * @return array
     */
    public function getCollectionTypes(Language $language): array
    {
        $qb = $this->getQuery();

        return $qb->addSelect(sprintf('(name->>\'%s\') AS label', $language->getCode()))
            ->execute()
            ->fetchAll();
    }

    public function autocomplete(
        Language $language,
        string $search = null,
        int $limit = null,
        string $field = null,
        ?string $order = 'ASC'
    ): array {
        $query = $this->connection->createQueryBuilder()
            ->select('id, code, COALESCE(name->>:language, null) as label')
            ->from(self::PRODUCT_COLLECTION_TYPE_TABLE, 'pct')
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
            ->select('p.id, p.code')
            ->from(self::PRODUCT_COLLECTION_TYPE_TABLE, 'p');
    }
}
