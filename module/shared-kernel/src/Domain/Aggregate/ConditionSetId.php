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
class ConditionSetId extends AggregateId
{
    public const NAMESPACE = '14343bf2-6c4c-47cc-92fc-3002a09521fc';

    /**
     * @param string $name
     *
     * @return ConditionSetId
     */
    public static function fromString(string $name): ConditionSetId
    {
        return new static(self::generateIdentifier(self::NAMESPACE, $name)->getValue());
    }
}
