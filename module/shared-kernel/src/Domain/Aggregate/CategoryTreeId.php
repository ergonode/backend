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
class CategoryTreeId extends AggregateId
{
    public const NAMESPACE = 'f39d019e-92f0-47e8-b5ee-81155e7ddfc2';

    /**
     * @param string $name
     *
     * @return CategoryTreeId
     */
    public static function fromKey(string $name): CategoryTreeId
    {
        return new static(self::generateIdentifier(self::NAMESPACE, $name)->getValue());
    }
}
