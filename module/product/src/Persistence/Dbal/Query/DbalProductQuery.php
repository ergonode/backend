<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\Product\Domain\Entity\ProductId;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Product\Domain\ValueObject\Sku;

/**
 */
class DbalProductQuery implements ProductQueryInterface
{
    private const PRODUCT_TABLE = 'product';
    private const TEMPLATE_TABLE = 'designer.template';

    /**
     * @var Connection
     */
    private $connection;

    /**
     * DbalProductQuery constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param Sku $sku
     *
     * @return array|null
     */
    public function findBySku(Sku $sku): ?array
    {
        $qb = $this->getQuery();
        $result = $qb->where($qb->expr()->eq('sku', ':sku'))
            ->setParameter(':sku', $sku->getValue())
            ->execute()
            ->fetch();
        if (false !== $result) {
            return $result;
        }

        return null;
    }

    /**
     * @return array
     */
    public function getAllIds(): array
    {
        return $this->connection->createQueryBuilder()
            ->select('id')
            ->from('product')
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * @param CategoryId $categoryId
     *
     * @return ProductId[]
     */
    public function findProductIdByCategoryId(CategoryId $categoryId): array
    {
        $qb = $this->getQuery();

        $result = [];
        $records = $qb
            ->select('id')
            ->where($qb->expr()->in('category', ':category'))
            ->setParameter(':category', $categoryId->getValue())
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);

        foreach ($records as $record) {
            $result[] = new ProductId($record);
        }

        return $result;
    }

    /**
     * @param TemplateId $templateId
     *
     * @return array
     */
    public function findProductIdByTemplateId(TemplateId $templateId): array
    {
        $qb = $this->getQuery();

        return $qb->select('p.id')
            ->where($qb->expr()->eq('p.template_id', ':templateId'))
            ->setParameter(':templateId', $templateId->getValue())
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('p.id, p.index, p.sku, p.version, t.name AS template')
            ->from(self::PRODUCT_TABLE, 'p')
            ->join('p', self::TEMPLATE_TABLE, 't', 't.id = p.template_id');
    }
}
