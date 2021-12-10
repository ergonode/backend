<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Application\Event;

use Ergonode\SharedKernel\Application\ApplicationEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

class ProductCompletenessCalculatedEvent implements ApplicationEventInterface
{
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
