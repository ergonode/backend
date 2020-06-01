<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Domain\Query\Builder;

use Doctrine\DBAL\Query\QueryBuilder;

/**
 */
interface DefaultImageQueryBuilderInterface
{
    /**
     * @param QueryBuilder $query
     * @param array        $info
     */
    public function addSelect(QueryBuilder $query, array $info): void;
}
