<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Domain\Factory;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionType;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionTypeId;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionTypeCode;

/**
 */
class ProductCollectionTypeFactory
{
    /**
     * @param ProductCollectionTypeId   $id
     * @param ProductCollectionTypeCode $code
     * @param TranslatableString        $name
     *
     * @return ProductCollectionType
     */
    public function create(
        ProductCollectionTypeId $id,
        ProductCollectionTypeCode $code,
        TranslatableString $name
    ): ProductCollectionType {
        return new ProductCollectionType(
            $id,
            $code,
            $name,
        );
    }
}
