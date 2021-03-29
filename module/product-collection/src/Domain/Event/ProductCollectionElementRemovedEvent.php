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

class ProductCollectionElementRemovedEvent implements AggregateEventInterface
{
    private ProductCollectionId $id;

    private ProductId $productId;

    private \DateTime $collectionEditedAt;

    public function __construct(ProductCollectionId $id, ProductId $productId, \DateTime $collectionEditedAt)
    {
        $this->id = $id;
        $this->productId = $productId;
        $this->collectionEditedAt = $collectionEditedAt;
    }


    public function getAggregateId(): ProductCollectionId
    {
        return $this->id;
    }

    public function getCollectionEditedAt(): \DateTime
    {
        return $this->collectionEditedAt;
    }

    public function getProductId(): ProductId
    {
        return $this->productId;
    }
}
