<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Domain\Query\Builder;

use Doctrine\DBAL\Query\QueryBuilder;

interface DefaultLabelQueryBuilderInterface
{
    public function addSelect(QueryBuilder $query, int $lft, int $rgt): void;
}
