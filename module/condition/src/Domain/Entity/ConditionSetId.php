<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Entity;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Segment\Domain\ValueObject\SegmentCode;
use Ramsey\Uuid\Uuid;

/**
 *
 */
class ConditionSetId extends AbstractId
{
    public const NAMESPACE = '14343bf2-6c4c-47cc-92fc-3002a09521fc';

    /**
     * @param SegmentCode $code
     *
     * @return ConditionSetId
     */
    public static function fromCode(SegmentCode $code): ConditionSetId
    {
        return new static(Uuid::uuid5(self::NAMESPACE, $code->getValue())->toString());
    }
}
