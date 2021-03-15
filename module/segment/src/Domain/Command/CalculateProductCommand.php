<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use JMS\Serializer\Annotation as JMS;

class CalculateProductCommand implements SegmentCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductId")
     */
    private ProductId $productId;

    public function __construct(ProductId $productId)
    {
        $this->productId = $productId;
    }

    public function getProductId(): ProductId
    {
        return $this->productId;
    }
}
