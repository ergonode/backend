<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Persistence\Dbal\DataSet;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractDbalDataSet;
use Ergonode\Grid\ColumnInterface;
use Ergonode\Grid\Builder\DbalDataSetQueryBuilder;
use Ergonode\Grid\Request\FilterValueCollection;
use Webmozart\Assert\Assert;

/**
 */
class DbalProductDataSet extends AbstractDbalDataSet
{
    private const PRODUCT_TABLE = 'product';

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var DbalDataSetQueryBuilder
     */
    private $provider;

    /**
     * @param Connection              $connection
     * @param DbalDataSetQueryBuilder $provider
     */
    public function __construct(Connection $connection, DbalDataSetQueryBuilder $provider)
    {
        $this->connection = $connection;
        $this->provider = $provider;
    }

    /**
     * @param ColumnInterface[]     $columns
     * @param FilterValueCollection $values
     * @param int                   $limit
     * @param int                   $offset
     * @param string|null           $field
     * @param string                $order
     *
     * @return \Traversable
     * @throws \Exception
     */
    public function getItems(array $columns, FilterValueCollection $values, int $limit, int $offset, ?string $field = null, string $order = 'ASC'): \Traversable
    {
        Assert::allIsInstanceOf($columns, ColumnInterface::class);

        $userLanguage = new Language(Language::EN);
        $query = $this->getQuery();
        foreach ($columns as $key => $column) {
            $attribute = $column->getAttribute();
            $language = $column->getLanguage() ?: $userLanguage;
            if($attribute) {
                $this->provider->provide($query, $key, $attribute, $language);
            }

            if ($column->getField() === 'esa_category') {
                $query->addSelect(sprintf('(SELECT jsonb_agg(category_id) FROM product_category_product pcp WHERE pcp . product_id = p . id LIMIT 1) AS "esa_category:%s"', $language->getCode()));
            }
        }

        $qb = $this->connection->createQueryBuilder();
        $qb->select('*');
        $qb->from(sprintf('(%s)', $query->getSQL()), 't');

        $this->buildFilters($qb, $values, $columns);

        $qb->setMaxResults($limit);
        $qb->setFirstResult($offset);
        if ($field) {
            $qb->orderBy(sprintf('"%s"', $field), $order);
        }

        $result = $qb->execute()->fetchAll();

        return new ArrayCollection($result);
    }

    /**
     * @param FilterValueCollection $values
     * @param ColumnInterface[]     $columns
     *
     * @return int
     */
    public function countItems(FilterValueCollection $values, array $columns = []): int
    {
        Assert::allIsInstanceOf($columns, ColumnInterface::class);


        $language = new Language(Language::EN);
        $query = $this->getQuery();
        foreach ($columns as $key => $column) {
            $attribute = $column->getAttribute();
            if($attribute) {
                $this->provider->provide($query, $key, $attribute, $language);
            }
            if ($key === 'esa_category') {
                $query->addSelect(sprintf('(SELECT jsonb_agg(category_id) FROM product_category_product pcp WHERE pcp . product_id = p . id LIMIT 1) AS "esa_category:%s"', $language->getCode()));
            }
        }

        $qb = $this->connection->createQueryBuilder();
        $qb->select('*');
        $qb->from(sprintf('(%s)', $query->getSQL()), 't');

        $this->buildFilters($qb, $values, $columns);
        $count = $qb->select('count(*) AS COUNT')
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);

        if ($count) {
            return $count;
        }

        return 0;
    }

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('p.id, p.index, p.sku, p.version, p.template_id AS esa_template')
            ->from(self::PRODUCT_TABLE, 'p');
    }
}
