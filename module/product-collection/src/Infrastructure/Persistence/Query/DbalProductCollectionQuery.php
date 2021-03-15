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
use Ergonode\ProductCollection\Domain\Query\ProductCollectionQueryInterface;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionCode;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

class DbalProductCollectionQuery implements ProductCollectionQueryInterface
{
    private const PRODUCT_COLLECTION_TABLE = 'public.product_collection';
    private const PRODUCT_COLLECTION_ELEMENT_TABLE = 'public.product_collection_element';

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
        $qb = $this->getQuery();

        return $qb
            ->select('id', 'code')
            ->execute()
            ->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    /**
     * @return string[]
     */
    public function getOptions(Language $language): array
    {
        $qb = $this->getQuery();

        return $qb
            ->select('id', 'code')
            ->addSelect(sprintf('(name->>\'%s\') AS name', $language->getCode()))
            ->execute()
            ->fetchAll();
    }

    public function findIdByCode(ProductCollectionCode $code): ?ProductCollectionId
    {
        $qb = $this->connection->createQueryBuilder();
        $result = $qb->select('id')
            ->from(self::PRODUCT_COLLECTION_TABLE, 'c')
            ->where($qb->expr()->eq('code', ':code'))
            ->setParameter(':code', $code->getValue())
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);

        if ($result) {
            return new ProductCollectionId($result);
        }

        return null;
    }

    /**
     * @return mixed|void
     */
    public function findCollectionIdsByCollectionTypeId(ProductCollectionTypeId $id)
    {
        $qb = $this->connection->createQueryBuilder()
            ->select('id')
            ->from(self::PRODUCT_COLLECTION_TABLE, 'c');

        $result = $qb
            ->where($qb->expr()->eq('type_id', ':id'))
            ->setParameter(':id', $id->getValue())
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);

        if (false === $result) {
            $result = [];
        }

        foreach ($result as &$item) {
            $item = new ProductCollectionId($item);
        }

        return $result;
    }

    /**
     * @return ProductCollectionId[]
     */
    public function findProductCollectionIdByProductId(ProductId $id): array
    {
        $qb = $this->connection->createQueryBuilder();
        $result = $qb->select('pc.product_collection_id')
            ->from(self::PRODUCT_COLLECTION_ELEMENT_TABLE, 'pc')
            ->where($qb->expr()->eq('product_id', ':id'))
            ->setParameter(':id', $id->getValue())
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);

        if (false === $result) {
            $result = [];
        }

        foreach ($result as &$item) {
            $item = new ProductCollectionId($item);
        }

        return $result;
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
            ->from(self::PRODUCT_COLLECTION_TABLE, 'pc')
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
            ->select('COALESCE(t.elements_count, 0) AS elements_count')
            ->from(self::PRODUCT_COLLECTION_TABLE, 'c')
            ->leftJoin(
                'c',
                '(SELECT count(*) as elements_count, ec.product_collection_id FROM '.
                'product_collection_element ec GROUP BY ec.product_collection_id)',
                't',
                't.product_collection_id = c.id'
            );
    }
}
