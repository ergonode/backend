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
class ProductId extends AggregateId
{
    public const NAMESPACE = '7cf84041-304b-41c9-8401-139d9203735e';

    /**
     * @param string $name
     *
     * @return ProductId
     */
    public static function fromString(string $name): ProductId
    {
        return new static(self::generateIdentifier(self::NAMESPACE, $name)->getValue());
    }
}
