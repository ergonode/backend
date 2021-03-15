<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Domain\Query;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Doctrine\DBAL\Query\QueryBuilder;

interface ProductCollectionElementGridQueryInterface
{
    public function getGridQuery(ProductCollectionId $productCollectionId, Language $language): QueryBuilder;
}
