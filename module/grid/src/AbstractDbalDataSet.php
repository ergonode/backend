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
use Ergonode\Grid\Request\FilterValue;
use Ergonode\Grid\Request\FilterValueCollection;
use Ramsey\Uuid\Uuid;

abstract class AbstractDbalDataSet implements DataSetInterface
{
    public const NAMESPACE = 'b2e8fb6d-e1ac-4322-bd54-6e78926ba365';

    /**
     * @param ColumnInterface[] $columns
     */
    protected function buildFilters(QueryBuilder $query, FilterValueCollection $values, array $columns = []): void
    {
        /** @var FilterValue[] $filters */
        foreach ($values as $name => $filters) {
            if (array_key_exists($name, $columns)) {
                $columnFilter = $columns[$name]->getFilter();
                if ($columnFilter) {
                    if ($columns[$name]->getAttribute()) {
                        $name = Uuid::uuid5(self::NAMESPACE, $name)->toString();
                    }
                    foreach ($filters as $filter) {
                        if ($columnFilter instanceof TextFilter) {
                            $this->buildTextQuery($query, $name, $filter->getOperator(), $filter->getValue());
                        } elseif ($columnFilter instanceof MultiSelectFilter) {
                            $this->buildMultiSelectQuery(
                                $query,
                                $name,
                                $filter->getOperator(),
                                $filter->getValue()
                            );
                        } else {
                            $this->buildDefaultQuery($query, $name, $filter->getOperator(), $filter->getValue());
                        }
                    }
                }
            }
        }
    }

    private function buildMultiSelectQuery(
        QueryBuilder $query,
        string $field,
        string $operator,
        string $givenValue = null
    ): void {
        if ('=' === $operator) {
            if (null !== $givenValue) {
                $values = explode(',', $givenValue);

                $fields = [];
                foreach ($values as $value) {
                    $fields[] =
                        sprintf(
                            'jsonb_exists_any(to_json("%s")::jsonb, %s::text[])',
                            $field,
                            $query->createNamedParameter(sprintf('{%s}', $value))
                        );
                }
                $query->andWhere(implode(' OR ', $fields));
            } else {
                $query->andWhere(
                    $query->expr()->orX(
                        $query->expr()->eq(sprintf('"%s"::TEXT', $field), '\'[]\''),
                        $query->expr()->isNull(sprintf('"%s"', $field)),
                    )
                );
            }
        }

        if ('!=' === $operator) {
            if (null !== $givenValue) {
                $values = explode(',', $givenValue);

                $fields = [];
                foreach ($values as $value) {
                    $fields[] =
                        sprintf(
                            'NOT jsonb_exists_any(to_json("%s")::jsonb, %s::text[])',
                            $field,
                            $query->createNamedParameter(sprintf('{%s}', $value))
                        );
                }
                $query->andWhere(implode(' AND ', $fields));
            } else {
                $query->andWhere(
                    $query->expr()->andX(
                        $query->expr()->neq(sprintf('"%s"::TEXT', $field), '\'[]\''),
                        $query->expr()->isNotNull(sprintf('"%s"', $field)),
                    )
                );
            }
        }
    }

    private function buildDefaultQuery(QueryBuilder $query, string $field, string $operator, string $value = null): void
    {
        $query->andWhere($this->getExpresion($query, $field, $operator, $value));
    }

    private function buildTextQuery(QueryBuilder $query, string $field, string $operator, string $value = null): void
    {
        $query->andWhere($this->getExpresion($query, $field, $operator, $value));
    }

    private function getExpresion(QueryBuilder $query, string $field, string $operator, ?string $value = null): string
    {
        $field = sprintf('"%s"', $field);

        if ('>=' === $operator) {
            return $query->expr()->gte($field, $query->createNamedParameter($value));
        }

        if ('<=' === $operator) {
            return $query->expr()->lte($field, $query->createNamedParameter($value));
        }

        if ('!=' === $operator) {
            if (null !== $value) {
                return \sprintf(
                    '%s::TEXT NOT ILIKE %s',
                    $field,
                    $query->createNamedParameter(\sprintf('%%%s%%', $this->escape($value)))
                );
            }

            return $query->expr()->isNotNull($field);
        }

        if (null !== $value) {
            return \sprintf(
                '%s::TEXT ILIKE %s',
                $field,
                $query->createNamedParameter(\sprintf('%%%s%%', $this->escape($value)))
            );
        }

        return $query->expr()->isNull($field);
    }

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
