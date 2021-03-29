<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Domain\Event;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;

class ProductCollectionElementVisibleChangedEvent implements AggregateEventInterface
{
    private ProductCollectionId $id;

    private ProductId $productId;

    private bool $visible;

    public function __construct(ProductCollectionId $id, ProductId $productId, bool $visible)
    {
        $this->id = $id;
        $this->productId = $productId;
        $this->visible = $visible;
    }

    public function getAggregateId(): ProductCollectionId
    {
        return $this->id;
    }

    public function getProductId(): ProductId
    {
        return $this->productId;
    }

    public function isVisible(): bool
    {
        return $this->visible;
    }
}
