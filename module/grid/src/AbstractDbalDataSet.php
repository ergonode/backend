<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Grid\Filter\MultiSelectFilter;
use Ergonode\Grid\Filter\TextFilter;

/**
 */
abstract class AbstractDbalDataSet implements DataSetInterface
{
    /**
     * @param QueryBuilder      $query
     * @param ColumnInterface[] $columns
     */
    protected function buildFilters(QueryBuilder $query, array $columns = []): void
    {
        foreach ($columns as $field => $column) {
            $filter = $column->getFilter();
            if ($filter && !empty($filter->getValues())) {
                if ($filter instanceof TextFilter) {
                    $this->buildTextQuery($query, $field, $filter->getValues());
                } elseif ($filter instanceof MultiSelectFilter) {
                    $this->buildMultiSelectQuery($query, $field, $filter->getValues());
                } else {
                    $this->buildDefaultQuery($query, $field, $filter->getValues());
                }
            }
        }
    }

    /**
     * @param QueryBuilder $query
     * @param string       $field
     * @param string[]     $values
     */
    private function buildTextQuery(QueryBuilder $query, string $field, array $values = []): void
    {
        foreach ($values as $operator => $value) {
            if (null === $value) {
                $query->andWhere($query->expr()->isNull($field));
            } else {
                $query->andWhere($this->getExpresion($query, $field, $operator, $value));
            }
        }
    }

    /**
     * @param QueryBuilder $query
     * @param string       $field
     * @param string[]     $values
     */
    private function buildMultiSelectQuery(QueryBuilder $query, string $field, array $values = []): void
    {
        foreach ($values as $value) {
            if (null !== $value) {
                $query->andWhere(
                    \sprintf(
                        'jsonb_exists_any(%s, %s)',
                        $field,
                        $query->createNamedParameter(sprintf('{%s}', $value))
                    )
                );
            } else {
                $query->andWhere(sprintf('%s::TEXT = \'[]\'::TEXT', $field));
            }
        }
    }

    /**
     * @param QueryBuilder $query
     * @param string       $field
     * @param array        $values
     */
    private function buildDefaultQuery(QueryBuilder $query, string $field, array $values = []): void
    {
        foreach ($values as $operator => $value) {
            $query->andWhere($this->getExpresion($query, $field, $operator, $value));
        }
    }

    /**
     * @param QueryBuilder $query
     * @param string       $field
     * @param string       $operator
     * @param string       $value
     *
     * @return string
     */
    private function getExpresion(QueryBuilder $query, string $field, string $operator, ?string $value = null): string
    {
        if (null === $value) {
            return $query->expr()->isNull($field);
        }
        if ('>=' === $operator) {
            return $query->expr()->gte($field, $query->createNamedParameter($value));
        }
        if ('<=' === $operator) {
            return $query->expr()->lte($field, $query->createNamedParameter($value));
        }

        return $query->expr()->eq(sprintf('%s::TEXT', $field), $query->createNamedParameter($value));
    }

    /**
     * @param string $value
     *
     * @return string
     */
    private function escape(string $value): string
    {
        $replace = [
            '\\' => '\\\\',
            '%' => '\%',
            '_' => '\_',
        ];

        return str_replace(array_keys($replace), array_values($replace), $value);
    }
}
