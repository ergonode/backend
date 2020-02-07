<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\SharedKernel\Domain\Aggregate;

use Ergonode\SharedKernel\Domain\AggregateId;

/**
 */
class ProductCollectionTypeId extends AggregateId
{
    public const NAMESPACE = '5205c16c-5534-4aef-811a-f6bb5ef0dca2';

    /**
     * @param string $name
     *
     * @return ProductCollectionTypeId
     */
    public static function fromString(string $name): ProductCollectionTypeId
    {
        return new static(self::generateIdentifier(self::NAMESPACE, $name)->getValue());
    }
}
