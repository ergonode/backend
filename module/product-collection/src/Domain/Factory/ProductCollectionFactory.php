<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Domain\Factory;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\ProductCollection\Domain\Entity\ProductCollection;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionId;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionTypeId;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionCode;

/**
 */
class ProductCollectionFactory
{
    /**
     * @param ProductCollectionId     $id
     * @param ProductCollectionCode   $code
     * @param TranslatableString      $name
     * @param ProductCollectionTypeId $typeId
     *
     * @return ProductCollection
     */
    public function create(
        ProductCollectionId $id,
        ProductCollectionCode $code,
        TranslatableString $name,
        ProductCollectionTypeId $typeId
    ): ProductCollection {
        return new ProductCollection(
            $id,
            $code,
            $name,
            $typeId
        );
    }
}
