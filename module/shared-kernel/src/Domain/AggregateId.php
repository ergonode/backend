<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\SharedKernel\Domain;

use Ramsey\Uuid\Uuid;

class AggregateId extends AbstractId
{
    /**
     * @param string $namespace
     * @param string $value
     *
     * @return AggregateId
     */
    public static function generateIdentifier(string $namespace, string $value): AggregateId
    {
        return new static(Uuid::uuid5($namespace, $value)->toString());
    }
}
