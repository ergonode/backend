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
class CategoryId extends AggregateId
{
    public const NAMESPACE = '4438d266-ec62-473b-9f46-1a767e2060d4';

    /**
     * @param string $value
     *
     * @return CategoryId
     */
    public static function fromCode(string $value): CategoryId
    {
        return new static(self::generateIdentifier(self::NAMESPACE, $value)->getValue());
    }
}
