<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Domain\Entity;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionCode;
use Ramsey\Uuid\Uuid;

/**
 */
class ProductCollectionId extends AbstractId
{
    public const NAMESPACE = 'a6edc906-2f9f-5fb2-a373-efac406f0ef2';

    /**
     * @param ProductCollectionCode $code
     *
     * @return ProductCollectionId
     */
    public static function fromCode(ProductCollectionCode $code): ProductCollectionId
    {
        return new static(Uuid::uuid5(self::NAMESPACE, $code->getValue())->toString());
    }
}
