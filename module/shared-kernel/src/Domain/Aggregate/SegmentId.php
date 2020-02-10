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
class SegmentId extends AggregateId
{
    public const NAMESPACE = '9bf0935b-95e6-4808-9e47-f9b760a5ff5b';

    /**
     * @param string $code
     *
     * @return SegmentId
     */
    public static function fromCode(string $code): SegmentId
    {
        return new static(self::generateIdentifier(self::NAMESPACE, $code)->getValue());
    }
}
