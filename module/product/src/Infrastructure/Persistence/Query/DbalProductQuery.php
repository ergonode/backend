<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Types;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ramsey\Uuid\Uuid;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;

class DbalProductQuery implements ProductQueryInterface
{
    private const PRODUCT_TABLE = 'public.product';
    private const VALUE_TRANSLATION_TABLE = 'public.value_translation';
    private const VALUE_TABLE = 'public.product_value';
    private const SEGMENT_PRODUCT_TABLE = 'public.segment_product';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findProductIdBySku(Sku $sku): ?ProductId
    {
        $qb = $this->getQuery();
        $qb->select('id');
        $result = $qb->where($qb->expr()->eq('sku', ':sku'))
            ->setParameter(':sku', $sku->getValue())
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);

        if ($result) {
            return new ProductId($result);
        }

        return null;
    }

    public function findSkuByProductId(ProductId $id): ?Sku
    {
        $qb = $this->getQuery();
        $qb->select('sku');
        $result = $qb->where($qb->expr()->eq('id', ':id'))
            ->setParameter(':id', $id->getValue())
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);

        if ($result) {
            return new Sku($result);
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getAllIds(): array
    {
        $result = $this->connection->createQueryBuilder()
            ->select('id')
            ->from(self::PRODUCT_TABLE)
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);

        if (false !== $result) {
            return $result;
        }

        return [];
    }

    /**
     * @return array
     */
    public function getAllEditedIds(?\DateTime $dateTime = null): array
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->select('id')
            ->from(self::PRODUCT_TABLE);
        if ($dateTime) {
            $qb
                ->where($qb->expr()->gte('updated_at', ':updatedAt'))
                ->setParameter(':updatedAt', $dateTime, Types::DATETIMETZ_MUTABLE);
        }

        $result = $qb
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);

        if (false !== $result) {
            return $result;
        }

        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function getAllSkus(): array
    {
        $result = $this->connection->createQueryBuilder()
            ->select('sku')
            ->from(self::PRODUCT_TABLE)
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);

        if (false !== $result) {
            return $result;
        }

        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function getOthersIds(array $productIds): array
    {
        $query = $this->connection->createQueryBuilder()
            ->select('id')
            ->from(self::PRODUCT_TABLE, 'p');

        if ($productIds) {
            $query->andWhere($query->expr()->notIn('p.id', ':productId'))
                ->setParameter(':productId', $productIds, Connection::PARAM_STR_ARRAY);
        }

        $result = $query
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);

        //todo add where

        if (false !== $result) {
            return $result;
        }

        return [];
    }


    /**
     * {@inheritDoc}
     */
    public function findProductIdByCategoryId(CategoryId $categoryId): array
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->select('p.id')
            ->from('public.product', 'p')
            ->join('p', 'public.product_category', 'pcp', 'p.id = pcp.product_id')
            ->where($queryBuilder->expr()->in('pcp.category_id', ':category'))
            ->setParameter(':category', $categoryId->getValue());
        $result = $queryBuilder->execute()->fetchAll(\PDO::FETCH_COLUMN);

        if (false === $result) {
            return [];
        }

        foreach ($result as &$item) {
            $item = new ProductId($item);
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function findProductIdByAttributeId(AttributeId $attributeId, ?Uuid $valueId = null): array
    {
        $queryBuilder = $this->connection->createQueryBuilder()
            ->select('product_id')
            ->from(self::VALUE_TABLE)
            ->where('attribute_id = :attribute')
            ->setParameter('attribute', $attributeId->getValue());
        if ($valueId) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('value_id', ':valueId'));
            $queryBuilder->setParameter('valueId', $valueId->toString());
        }

        $result = $queryBuilder->execute()->fetchAll(\PDO::FETCH_COLUMN);

        if (false === $result) {
            return [];
        }

        foreach ($result as &$item) {
            $item = new ProductId($item);
        }

        return $result;
    }

    /**
     * @param array $skus
     *
     * @return ProductId[]
     */
    public function findProductIdsBySkus(array $skus = []): array
    {
        $qb = $this->connection->createQueryBuilder()
            ->select('id')
            ->from(self::PRODUCT_TABLE);

        if ($skus) {
            $qb->andWhere($qb->expr()->in('sku', ':skus'))
                ->setParameter(':skus', $skus, Connection::PARAM_STR_ARRAY);
        }

        $result = $qb->execute()->fetchAll(\PDO::FETCH_COLUMN);
        if (false === $result) {
            return [];
        }

        foreach ($result as &$item) {
            $item = new ProductId($item);
        }

        return $result;
    }

    /**
     * @param array $segmentIds
     *
     * @return array
     */
    public function findProductIdsBySegments(array $segmentIds): array
    {
        $qb = $this->connection->createQueryBuilder()
            ->select('product_id')
            ->from(self::SEGMENT_PRODUCT_TABLE);
        if ($segmentIds) {
            $result = $qb->andWhere($qb->expr()->in('segment_id', ':segmentIds'))
                ->andWhere('available = true')
                ->setParameter(':segmentIds', $segmentIds, Connection::PARAM_STR_ARRAY)
                ->execute()->fetchAll(\PDO::FETCH_COLUMN);
        }

        if (!isset($result) || false === $result) {
            return [];
        }

        foreach ($result as &$item) {
            $item = new ProductId($item);
        }

        return $result;
    }


    /**
     * @return array
     */
    public function getDictionary(): array
    {
        $query = $this->getQuery();

        return $query
            ->select('id', 'sku')
            ->execute()
            ->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    /**
     * @return ProductId[]
     */
    public function findProductIdsByTemplate(TemplateId $templateId): array
    {
        $qb = $this->connection->createQueryBuilder();
        $records = $qb->select('id')
            ->from(self::PRODUCT_TABLE)
            ->where($qb->expr()->eq('template_id', ':templateId'))
            ->setParameter('templateId', $templateId->getValue())
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);

        $result = [];
        foreach ($records as $record) {
            $result[] = new ProductId($record);
        }

        return $result;
    }

    /**
     * @return array|mixed|mixed[]
     */
    public function findProductIdByOptionId(AggregateId $id)
    {
        $qb = $this->connection->createQueryBuilder()
            ->select('pv.product_id')
            ->from(self::VALUE_TABLE, 'pv');

        $result = $qb
            ->join('pv', self::VALUE_TRANSLATION_TABLE, 'vt', 'vt.id = pv.value_id')
            ->andWhere($qb->expr()->eq('vt.value', ':optionId'))
            ->setParameter('optionId', $id->getValue())
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);

        if (false === $result) {
            $result = [];
        }

        foreach ($result as &$item) {
            $item = new ProductId($item);
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getMultimediaRelation(MultimediaId $id): array
    {
        $qb = $this->connection->createQueryBuilder();

        return $qb
            ->select('p.id, p.sku')
            ->from('value_translation', 'vt')
            ->join('vt', 'product_value', 'pv', 'pv.value_id = vt.id')
            ->join('pv', 'product', 'p', 'p.id = pv.product_id')
            ->andWhere('vt.value ILIKE :search')
            ->setParameter(':search', '%'.$id->getValue().'%')
            ->execute()
            ->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    /**
     * {@inheritDoc}
     */
    public function findProductIdByType(string $type): array
    {
        $qb = $this->connection->createQueryBuilder();

        return $qb
            ->select('id')
            ->from(self::PRODUCT_TABLE)
            ->where($qb->expr()->eq('type', ':type'))
            ->setParameter(':type', $type)
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * @return array
     */
    public function autocomplete(
        string $search = null,
        int $limit = null,
        string $field = null,
        ?string $order = 'ASC'
    ): array {
        $query = $this->connection->createQueryBuilder()
            ->select('id, sku as code')
            ->from(self::PRODUCT_TABLE);

        if ($search) {
            $query->orWhere('sku ILIKE :search');
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

    public function getCount(): int
    {
        return $this->connection->createQueryBuilder()
            ->select('count(*)')
            ->from(self::PRODUCT_TABLE)
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);
    }

    /**
     * @return ProductId[]
     */
    public function findAttributeIdsBySku(Sku $sku): array
    {
        $qb = $this->connection->createQueryBuilder();

        $result = $qb->select('attribute_id')
            ->from(self::VALUE_TABLE, 'vt')
            ->join('vt', self::PRODUCT_TABLE, 'pt', 'pt.id = vt.product_id')
            ->where($qb->expr()->eq('sku', ':sku'))
            ->setParameter(':sku', $sku->getValue())
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);

        if (false === $result) {
            $result = [];
        }

        foreach ($result as &$item) {
            $item = new ProductId($item);
        }

        return $result;
    }

    /**
     * @return AttributeId[]
     */
    public function findAttributeIdsByProductId(ProductId $productId): array
    {
        $qb = $this->connection->createQueryBuilder();

        $result = $qb->select('attribute_id')
            ->from(self::VALUE_TABLE, 'vt')
            ->where($qb->expr()->eq('product_id', ':productId'))
            ->setParameter(':productId', $productId->getValue())
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);

        if (false === $result) {
            $result = [];
        }

        foreach ($result as &$item) {
            $item = new AttributeId($item);
        }

        return $result;
    }

    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('p.id, p.index, p.sku')
            ->from(self::PRODUCT_TABLE, 'p');
    }
}
