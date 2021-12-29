<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Grid\Filter\Builder;

use Ergonode\Grid\Filter\Builder\FilterBuilderInterface;
use Ergonode\Grid\FilterInterface;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Grid\Request\FilterValue;
use Ergonode\Product\Infrastructure\Grid\Filter\RelationFilter;

class RelationFilterBuilder implements FilterBuilderInterface
{
    public function supports(FilterInterface $filter): bool
    {
        return ($filter instanceof RelationFilter);
    }

    public function build(QueryBuilder $query, string $field, FilterValue $filter): void
    {
        $operator = $filter->getOperator();
        $givenValue = $filter->getValue();

        if ('=' === $operator) {
            if (null !== $givenValue) {
                $query->andWhere($this->prepareQuery($query, $field, $givenValue));
            } else {
                $query->andWhere(
                    $query->expr()->orX(
                        $query->expr()->eq(sprintf('"%s"::TEXT', $field), '\'[]\''),
                        $query->expr()->isNull(sprintf('"%s"', $field)),
                    )
                );
            }
        } else {
            $query->expr()->isNull($field);
        }
    }

    protected function prepareQuery(QueryBuilder $query, string $field, ?string $value = null): string
    {
        $field = sprintf('"%s"', $field);

        return \sprintf(
            'ARRAY(SELECT jsonb_array_elements_text(%s))'
            .' && ARRAY(SELECT id::TEXT FROM product WHERE sku ilike %s)',
            $field,
            $query->createNamedParameter(\sprintf('%%%s%%', $this->escape($value)))
        );
    }

    protected function escape(string $value): string
    {
        $replace = [
            '\\' => '\\\\',
            '%' => '\%',
            '_' => '\_',
        ];

        return str_replace(array_keys($replace), array_values($replace), $value);
    }
}
