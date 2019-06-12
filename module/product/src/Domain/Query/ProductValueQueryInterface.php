<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Query;

use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Attribute\Domain\Entity\ValueId;
use Ergonode\Product\Domain\Entity\ProductId;

/**
 * Class DbalProductValueQuery
 */
interface ProductValueQueryInterface
{
    /**
     * @param ProductId   $productId
     * @param AttributeId $attributeId
     * @param ValueId     $valueId
     *
     * @return null|array
     */
    public function findProductValue(ProductId $productId, AttributeId $attributeId, ValueId $valueId): ?array;
}
