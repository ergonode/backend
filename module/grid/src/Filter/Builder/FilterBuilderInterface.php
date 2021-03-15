<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Filter\Builder;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Grid\FilterInterface;
use Ergonode\Grid\Request\FilterValue;

interface FilterBuilderInterface
{
    public function supports(FilterInterface $filter): bool;

    public function build(QueryBuilder $query, string $name, FilterValue $filter): void;
}
