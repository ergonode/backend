<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;

interface SegmentProductsQueryInterface
{
    /**
     * @return string[]
     */
    public function getProducts(SegmentId $segmentId): array;

    /**
     * @return array
     */
    public function getProductsByType(SegmentId $segmentId, string $type): array;
}
