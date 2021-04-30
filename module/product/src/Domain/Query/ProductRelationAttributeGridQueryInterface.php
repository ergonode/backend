<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Query;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\Entity\Attribute\ProductRelationAttribute;

interface ProductRelationAttributeGridQueryInterface
{
    public function getGridQuery(
        AbstractProduct $product,
        ProductRelationAttribute $attribute,
        Language $language
    ): QueryBuilder;
}
