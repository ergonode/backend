<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Domain\Factory;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionType;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionTypeCode;

class ProductCollectionTypeFactory
{
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
