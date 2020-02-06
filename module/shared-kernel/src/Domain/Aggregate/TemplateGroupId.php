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
class TemplateGroupId extends AggregateId
{
    public const NAMESPACE = 'fd811a92-55aa-4d86-97e8-031ed53637db';

    /**
     * @param string $value
     *
     * @return TemplateGroupId
     */
    public static function fromKey(string $value): TemplateGroupId
    {
        return new static(self::generateIdentifier(self::NAMESPACE, $value)->getValue());
    }
}
