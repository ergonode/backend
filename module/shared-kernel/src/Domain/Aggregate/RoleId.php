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
class RoleId extends AggregateId
{
    public const NAMESPACE = '6601b60b-1701-4db4-87da-944c03aae69f';

    /**
     * @param string $name
     *
     * @return RoleId
     */
    public static function fromString(string $name): RoleId
    {
        return new static(self::generateIdentifier(self::NAMESPACE, $name)->getValue());
    }
}
