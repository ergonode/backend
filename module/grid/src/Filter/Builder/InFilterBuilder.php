<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Filter\Builder;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Grid\Filter\InFilter;
use Ergonode\Grid\FilterInterface;
use Ergonode\Grid\Request\FilterValue;

class InFilterBuilder implements FilterBuilderInterface
{
    public function supports(FilterInterface $filter): bool
    {
        return $filter instanceof InFilter;
    }

    public function build(QueryBuilder $query, string $name, FilterValue $filter): void
    {
        if (null === $filter->getValue()) {
            return;
        }

        $values = explode(',', $filter->getValue());

        if ('=' === $filter->getOperator()) {
            $query->andWhere(
                $query->expr()->in(
                    sprintf('%s::TEXT', $name),
                    $query->createNamedParameter($values, \Doctrine\DBAL\Connection::PARAM_STR_ARRAY)
                )
            );

            return;
        }

        if ('!=' === $filter->getOperator()) {
            $query->andWhere(
                $query->expr()->notIn(
                    sprintf('%s::TEXT', $name),
                    $query->createNamedParameter($values, \Doctrine\DBAL\Connection::PARAM_STR_ARRAY)
                )
            );
        }
    }
}
