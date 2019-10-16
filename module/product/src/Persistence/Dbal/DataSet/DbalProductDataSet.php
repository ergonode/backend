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
use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractDbalDataSet;
use Ergonode\Grid\Column\MultiSelectColumn;
use Ergonode\Grid\ColumnInterface;
use Ergonode\Grid\FilterInterface;
use Webmozart\Assert\Assert;

/**
 */
class DbalProductDataSet extends AbstractDbalDataSet
{
    private const PRODUCT_TABLE = 'product';
    private const TEMPLATE_TABLE = 'designer.template';

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
     * @param array       $columns
     * @param int         $limit
     * @param int         $offset
     * @param string|null $field
     * @param string      $order
     *
     * @return \Traversable
     * @throws \Exception
     */
    public function getItems(array $columns, int $limit, int $offset, ?string $field = null, string $order = 'ASC'): \Traversable
    {
        Assert::allIsInstanceOf($columns, ColumnInterface::class);

        $userLanguage = new Language(Language::EN);
        $query = $this->getQuery();
        foreach ($columns as $key => $column) {
            $language = $column->getLanguage() ?: $userLanguage;
            if (!in_array($column->getField(), ['id', 'sku', 'index', 'version', 'template'])) {
                if ($column->getType() === MultiSelectColumn::TYPE) {
                    $query->addSelect(sprintf(
                        '(SELECT jsonb_agg(value) FROM value_translation vt JOIN product_value pv ON  pv.value_id = vt.value_id WHERE pv.attribute_id = \'%s\' AND (vt.language = \'%s\' OR vt.language IS NULL) AND pv.product_id = p.id LIMIT 1) AS "%s"',
                        AttributeId::fromKey(new AttributeCode($column->getField()))->getValue(),
                        $language->getCode(),
                        $key
                    ));
                } else {
                    $query->addSelect(sprintf(
                        '(SELECT value FROM value_translation vt JOIN product_value pv ON  pv.value_id = vt.value_id  WHERE pv.attribute_id = \'%s\' AND (vt.language = \'%s\' OR vt.language IS NULL) AND pv.product_id = p.id LIMIT 1) AS "%s"',
                        AttributeId::fromKey(new AttributeCode($column->getField()))->getValue(),
                        $language->getCode(),
                        $key
                    ));
                }
            }
        }

        $qb = $this->connection->createQueryBuilder();
        $qb->select('*');
        $qb->from(sprintf('(%s)', $query->getSQL()), 't');

        $this->buildFilters($qb, $columns);

        $qb->setMaxResults($limit);
        $qb->setFirstResult($offset);
        if ($field) {
            $qb->orderBy(sprintf('"%s"', $field), $order);
        }

        $result = $qb->execute()->fetchAll();

        return new ArrayCollection($result);
    }

    /**
     * @param FilterInterface[] $filters
     *
     * @return int
     */
    public function countItems(array $filters = []): int
    {
        Assert::allIsInstanceOf($filters, ColumnInterface::class);

        $language = new Language(Language::EN);
        $query = $this->getQuery();
        foreach ($filters as $key => $column) {
            if (!in_array($key, ['id', 'sku', 'index', 'version', 'template', 'edit'])) {
                $query->addSelect(\sprintf('(SELECT value FROM value_translation vt JOIN product_value pv ON  pv.value_id = vt.value_id JOIN attribute a ON a.id = pv.attribute_id WHERE a.code = \'%s\' AND (vt.language = \'%s\' OR vt.language IS NULL) AND pv.product_id = p.id) AS "%s"', $key, $language->getCode(), $key));
            }
        }

        $qb = $this->connection->createQueryBuilder();
        $qb->select('*');
        $qb->from(sprintf('(%s)', $query->getSQL()), 't');

        $this->buildFilters($qb, $filters);
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
            ->select('p.id, p.index, p.sku, p.version, t.name AS template')
            ->from(self::PRODUCT_TABLE, 'p')
            ->join('p', self::TEMPLATE_TABLE, 't', 't.id = p.template_id');
    }
}
