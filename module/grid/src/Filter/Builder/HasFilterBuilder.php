<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Filter\Builder;

use Ergonode\Grid\Filter\HasFilter;
use Ergonode\Grid\FilterInterface;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Grid\Request\FilterValue;

class HasFilterBuilder extends AbstractFilterBuilder implements FilterBuilderInterface
{
    public function supports(FilterInterface $filter): bool
    {
        return ($filter instanceof HasFilter);
    }

    public function build(QueryBuilder $query, string $field, FilterValue $filter): void
    {
        $operator = $filter->getOperator();
        $givenValue = $filter->getValue();
        $query->andWhere($this->getExpresion($query, $field, $operator, $givenValue));
    }
}
