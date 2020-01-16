<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Entity;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ramsey\Uuid\Uuid;

/**
 */
class ProductId extends AbstractId
{
    public const NAMESPACE = '7cf84041-304b-41c9-8401-139d9203735e';

    /**
     * @param string $name
     *
     * @return ProductId
     */
    public static function fromString(string $name): ProductId
    {
        return new static(Uuid::uuid5(self::NAMESPACE, $name)->toString());
    }
}
