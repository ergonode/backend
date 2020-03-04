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
use Ergonode\Grid\Request\FilterValueCollection;
use Ergonode\Product\Infrastructure\Grid\Builder\DataSetQueryBuilder;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

/**
 */
class DbalProductDataSet extends AbstractDbalDataSet
{
    private const PRODUCT_TABLE = 'product';

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var DataSetQueryBuilder
     */
    private DataSetQueryBuilder $provider;

    /**
     * @var array
     */
    private array $names;

    /**
     * @param Connection          $connection
     * @param DataSetQueryBuilder $provider
     */
    public function __construct(Connection $connection, DataSetQueryBuilder $provider)
    {
        $this->connection = $connection;
        $this->provider = $provider;
        $this->names = [];
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
     *
     * @throws \Exception
     */
    public function getItems(
        array $columns,
        FilterValueCollection $values,
        int $limit,
        int $offset,
        ?string $field = null,
        string $order = 'ASC'
    ): \Traversable {
        $query = $this->build($columns);

        $qb = $this->connection->createQueryBuilder();
        $qb->select('*');
        $qb->from(sprintf('(%s)', $query->getSQL()), 't');

        $this->buildFilters($qb, $values, $columns);

        $qb->setMaxResults($limit);
        $qb->setFirstResult($offset);
        if ($field) {
            if ($columns[$field]->getAttribute()) {
                $field = Uuid::uuid5(self::NAMESPACE, $field)->toString();
            }
            $qb->orderBy(sprintf('"%s"', $field), $order);
        }

        $result = [];
        foreach ($qb->execute()->fetchAll() as $row => $record) {
            foreach ($record as $key => $value) {
                if (isset($this->names[$key])) {
                    $result[$row][$this->names[$key]] = $value;
                } else {
                    $result[$row][$key] = $value;
                }
            }
        }

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
        $query = $this->build($columns);

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
     * @param array $columns
     *
     * @return QueryBuilder
     */
    private function build(array $columns): QueryBuilder
    {
        Assert::allIsInstanceOf($columns, ColumnInterface::class);

        $userLanguage = new Language(Language::EN);
        $query = $this->getQuery();
        foreach ($columns as $key => $column) {
            $attribute = $column->getAttribute();
            $language = $column->getLanguage() ?: $userLanguage;
            if ($attribute) {
                $hash = Uuid::uuid5(AbstractDbalDataSet::NAMESPACE, $key)->toString();
                $this->names[$hash] = $key;
                $this->provider->provide($query, $hash, $attribute, $language);
            }
        }

        return $query;
    }

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('p.id, p.index, p.sku, p.version')
            ->from(self::PRODUCT_TABLE, 'p');
    }
}
