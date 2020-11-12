<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use JMS\Serializer\Annotation as JMS;

class CalculateProductInSegmentCommand implements SegmentCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\SegmentId")
     */
    private SegmentId $segmentId;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductId")
     */
    private ProductId $productId;

    public function __construct(SegmentId $segmentId, ProductId $productId)
    {
        $this->segmentId = $segmentId;
        $this->productId = $productId;
    }

    public function getSegmentId(): SegmentId
    {
        return $this->segmentId;
    }

    public function getProductId(): ProductId
    {
        return $this->productId;
    }
}
