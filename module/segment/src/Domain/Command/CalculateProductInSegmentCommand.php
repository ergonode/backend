<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Segment\Domain\Entity\SegmentId;
use JMS\Serializer\Annotation as JMS;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;

/**
 */
class CalculateProductInSegmentCommand implements DomainCommandInterface
{
    /**
     * @var SegmentId
     *
     * @JMS\Type("Ergonode\Segment\Domain\Entity\SegmentId")
     */
    private SegmentId $segmentId;

    /**
     * @var ProductId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductId")
     */
    private ProductId $productId;

    /**
     * @param SegmentId $segmentId
     * @param ProductId $productId
     */
    public function __construct(SegmentId $segmentId, ProductId $productId)
    {
        $this->segmentId = $segmentId;
        $this->productId = $productId;
    }

    /**
     * @return SegmentId
     */
    public function getSegmentId(): SegmentId
    {
        return $this->segmentId;
    }

    /**
     * @return ProductId
     */
    public function getProductId(): ProductId
    {
        return $this->productId;
    }
}
