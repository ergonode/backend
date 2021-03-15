<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\SharedKernel\Domain\Aggregate;

use Ergonode\SharedKernel\Domain\AggregateId;

class StatusId extends AggregateId
{
    public const NAMESPACE = 'dcf14212-d63d-4829-b914-71e3d5599ad2';

    public static function fromCode(string $code): StatusId
    {
        return new static(self::generateIdentifier(self::NAMESPACE, $code)->getValue());
    }
}
