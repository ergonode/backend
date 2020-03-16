<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use Faker\Provider\Base as BaseProvider;
use Ramsey\Uuid\Uuid;

/**
 */
class SegmentIdFaker extends BaseProvider
{
    private const NAMESPACE = '9bf0935b-95e6-4808-9e47-f9b760a5ff5b';

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
            return new SegmentId(Uuid::uuid5(self::NAMESPACE, $code)->toString());
        }

        return SegmentId::generate();
    }
}
