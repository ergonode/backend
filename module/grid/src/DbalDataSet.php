<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Grid\Filter\MultiSelectFilter;
use Ergonode\Grid\Filter\TextFilter;

/**
 */
class DbalDataSet implements DataSetInterface
{
    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    /**
     * @param QueryBuilder $queryBuilder
     */
    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = clone $queryBuilder;
    }

    /**
     * @param ColumnInterface[] $columns
     * @param array             $filters
     * @param int               $limit
     * @param int               $offset
     * @param string|null       $field
     * @param string            $order
     *
     * @return \Traversable
     */
    public function getItems(array $columns, array $filters, int $limit, int $offset, ?string $field = null, string $order = 'ASC'): \Traversable
    {
        $queryBuilder = clone $this->queryBuilder;
        $this->buildFilters($queryBuilder, $filters);
        $queryBuilder->setMaxResults($limit);
        $queryBuilder->setFirstResult($offset);
        if ($field) {
            $queryBuilder->orderBy($field, $order);
        }
        $result = $queryBuilder->execute()->fetchAll();

        return new ArrayCollection($result);
    }

    /**
     * @param array $filters
     *
     * @return int
     */
    public function countItems(array $filters = []): int
    {
        $cloneQuery = clone $this->queryBuilder;
        $this->buildFilters($cloneQuery, $filters);
        $count = $cloneQuery->select('count(*) AS COUNT')
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);

        if ($count) {
            return $count;
        }

        return 0;
    }

    /**
     * @param QueryBuilder      $query
     * @param FilterInterface[] $filters
     */
    private function buildFilters(QueryBuilder $query, array $filters = []): void
    {
        foreach ($filters as $field => $filter) {
            if ($filter instanceof TextFilter && !$filter->isEqual()) {
                $value = $filter->getValue();
                if ($value === null) {
                    $query->andWhere($query->expr()->isNull($field));
                } else {
                    $query->andWhere(
                        \sprintf(
                            '%s::TEXT ILIKE %s',
                            $field,
                            $query->createNamedParameter(\sprintf('%%%s%%', $this->escape(reset($value))))
                        )
                    );
                }
            } elseif ($filter instanceof MultiSelectFilter) {
                $value = $filter->getValue();
                if (is_string($filter->getValue())) {
                    $value = [$value];
                }
                if (!empty($value)) {
                    $query->andWhere(
                        \sprintf(
                            'jsonb_exists_any(%s, %s)',
                            $field,
                            $query->createNamedParameter(sprintf('{%s}', implode(',', $value)))
                        )
                    );
                } else {
                    $query->andWhere(sprintf('%s::TEXT = \'[]\'::TEXT', $field));
                }
            } elseif (!empty($filter->getValue())) {
                $value = $filter->getValue();
                $query->andWhere(
                    $query->expr()->eq(
                        $field,
                        $query->createNamedParameter(reset($value))
                    )
                );
            } else {
                $query->andWhere($query->expr()->isNull($field));
            }
        }
    }

    /**
     * @param string $value
     *
     * @return string
     */
    private function escape(string $value): string
    {
        $replace  = [
            '\\' => '\\\\',
            '%' => '\%',
            '_' => '\_',
        ];

        return str_replace(array_keys($replace), array_values($replace), $value);
    }
}
