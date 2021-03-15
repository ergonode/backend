<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Query;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Doctrine\DBAL\Query\QueryBuilder;

interface ProductCategoryGridQueryInterface
{
    public function getGridQuery(Language $language, ProductId $productId): QueryBuilder;
}
