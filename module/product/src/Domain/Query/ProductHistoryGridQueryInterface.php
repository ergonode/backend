<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Doctrine\DBAL\Query\QueryBuilder;

interface ProductHistoryGridQueryInterface
{
    public function getGridQuery(ProductId $id): QueryBuilder;
}
