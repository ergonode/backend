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
class TemplateId extends AggregateId
{
    public const NAMESPACE = '155fc493-1938-49e3-bfb7-393e11f6ee34';

    /**
     * @param string $value
     *
     * @return TemplateId
     */
    public static function fromKey(string $value): TemplateId
    {
        return new static(self::generateIdentifier(self::NAMESPACE, $value)->getValue());
    }
}
