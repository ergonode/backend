<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\SharedKernel\Domain\Aggregate;

use Ergonode\SharedKernel\Domain\AggregateId;

class AttributeId extends AggregateId
{
    public const NAMESPACE = 'eb5fa5eb-ecda-4ff6-ac91-9ac817062635';

    public static function fromKey(string $value): AttributeId
    {
        return new static(self::generateIdentifier(self::NAMESPACE, $value)->getValue());
    }
}
