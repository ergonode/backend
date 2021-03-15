<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Filter\Builder;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Grid\Filter\DateFilter;
use Ergonode\Grid\FilterInterface;
use Ergonode\Grid\Request\FilterValue;

class DateFilterBuilder extends AbstractFilterBuilder implements FilterBuilderInterface
{
    public function supports(FilterInterface $filter): bool
    {
        return ($filter instanceof DateFilter);
    }

    public function build(QueryBuilder $query, string $field, FilterValue $filter): void
    {
        $operator = $filter->getOperator();
        $givenValue = $filter->getValue();
        $query->andWhere($this->getExpresion($query, $field, $operator, $givenValue));
    }
}
