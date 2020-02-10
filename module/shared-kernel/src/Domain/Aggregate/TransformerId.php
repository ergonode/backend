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
class TransformerId extends AggregateId
{
    public const NAMESPACE = '9bbd658e-f383-4af3-8e07-308bf3375827';

    /**
     * @param string $value
     *
     * @return TransformerId
     */
    public static function fromKey(string $value): TransformerId
    {
        return new static(self::generateIdentifier(self::NAMESPACE, $value)->getValue());
    }
}
