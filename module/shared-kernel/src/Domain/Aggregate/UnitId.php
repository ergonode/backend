<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\SharedKernel\Domain\Aggregate;

use Ergonode\SharedKernel\Domain\AggregateId;

/**
 */
class UnitId extends AggregateId
{
    public const NAMESPACE = '4e54d9f7-f152-4eab-90e9-b74f7b53b4e1';

    /**
     * @param string $code
     *
     * @return UnitId
     */
    public static function fromCode(string $code): UnitId
    {
        return new static(self::generateIdentifier(self::NAMESPACE, $code)->getValue());
    }
}
