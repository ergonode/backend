<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Application\Event;

use Ergonode\SharedKernel\Application\ApplicationEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;

class ProductIncludedInSegmentEvent implements ApplicationEventInterface
{
    private ProductId $productId;
    private SegmentId $segmentId;

    public function __construct(ProductId $productId, SegmentId $segmentId)
    {
        $this->productId = $productId;
        $this->segmentId = $segmentId;
    }

    public function getProductId(): ProductId
    {
        return $this->productId;
    }

    public function getSegmentId(): SegmentId
    {
        return $this->segmentId;
    }
}
