<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\SharedKernel\Domain\Aggregate;

use Ergonode\SharedKernel\Domain\AggregateId;

class AttributeId extends AggregateId
{
    public const NAMESPACE = 'eb5fa5eb-ecda-4ff6-ac91-9ac817062635';

    /**
     * @deprecated
     */
    public static function fromKey(string $value): AttributeId
    {
        @trigger_error(
            'Ergonode\SharedKernel\Domain\Aggregate::fromKey is deprecated and will be removed in 2.0.',
            \E_USER_DEPRECATED,
        );

        return new static(self::generateIdentifier(self::NAMESPACE, $value)->getValue());
    }
}
