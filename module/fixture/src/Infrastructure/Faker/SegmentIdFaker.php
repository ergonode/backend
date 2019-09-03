<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\Segment\Domain\Entity\SegmentId;
use Ergonode\Segment\Domain\ValueObject\SegmentCode;
use Faker\Provider\Base as BaseProvider;

/**
 */
class SegmentIdFaker extends BaseProvider
{
    /**
     * @param string|null $code
     *
     * @return SegmentId
     *
     * @throws \Exception
     */
    public function segmentId(?string $code = null): SegmentId
    {
        if ($code) {
            return SegmentId::fromCode(new SegmentCode($code));
        }

        return SegmentId::generate();
    }
}
