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
class ProductCollectionElementId extends AggregateId
{
    public const NAMESPACE = 'b0819e5c-d5fa-40ac-aecd-05efb5142384';

    /**
     * @param string $name
     *
     * @return ProductCollectionElementId
     */
    public static function fromString(string $name): ProductCollectionElementId
    {
        return new static(self::generateIdentifier(self::NAMESPACE, $name)->getValue());
    }
}
