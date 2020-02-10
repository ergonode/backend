<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Domain\Entity;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionTypeCode;
use Ramsey\Uuid\Uuid;

/**
 */
class ProductCollectionTypeId extends AbstractId
{
    public const NAMESPACE = '5205c16c-5534-4aef-811a-f6bb5ef0dca2';

    /**
     * @param ProductCollectionTypeCode $code
     *
     * @return ProductCollectionTypeId
     */
    public static function fromCode(ProductCollectionTypeCode $code): ProductCollectionTypeId
    {
        return new static(Uuid::uuid5(self::NAMESPACE, $code->getValue())->toString());
    }
}
