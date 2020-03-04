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
class ProductCollectionId extends AggregateId
{
    public const NAMESPACE = 'a6edc906-2f9f-5fb2-a373-efac406f0ef2';

    /**
     * @param string $code
     *
     * @return ProductCollectionId
     */
    public static function fromCode(string $code): ProductCollectionId
    {
        return new static(self::generateIdentifier(self::NAMESPACE, $code)->getValue());
    }
}
