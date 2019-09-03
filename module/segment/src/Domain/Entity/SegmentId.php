<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Domain\Entity;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Segment\Domain\ValueObject\SegmentCode;
use Ramsey\Uuid\Uuid;

/**
 *
 */
class SegmentId extends AbstractId
{
    public const NAMESPACE = '9bf0935b-95e6-4808-9e47-f9b760a5ff5b';

    /**
     * @param SegmentCode $code
     *
     * @return SegmentId
     */
    public static function fromCode(SegmentCode $code): SegmentId
    {
        return new static(Uuid::uuid5(self::NAMESPACE, $code->getValue())->toString());
    }
}
