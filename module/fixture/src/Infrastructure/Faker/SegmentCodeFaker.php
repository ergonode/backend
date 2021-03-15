<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\Segment\Domain\ValueObject\SegmentCode;
use Faker\Provider\Base as BaseProvider;

class SegmentCodeFaker extends BaseProvider
{
    /**
     * @throws \Exception
     */
    public function segmentCode(?string $code = null): SegmentCode
    {
        if ($code) {
            return new SegmentCode($code);
        }

        return new SegmentCode(sprintf('code_%s_%s', random_int(1, 1000000), random_int(1, 1000000)));
    }
}
