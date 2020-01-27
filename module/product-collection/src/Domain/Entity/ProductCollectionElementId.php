<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Domain\Entity;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ramsey\Uuid\Uuid;

/**
 */
class ProductCollectionElementId extends AbstractId
{
    public const NAMESPACE = 'b0819e5c-d5fa-40ac-aecd-05efb5142384';

    /**
     * @param string $name
     *
     * @return ProductCollectionElementId
     */
    public static function fromString(string $name): ProductCollectionElementId
    {
        return new static(Uuid::uuid5(self::NAMESPACE, $name)->toString());
    }
}
