<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use JMS\Serializer\Annotation as JMS;

class ProductCollectionElementRemovedEvent implements DomainEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId")
     */
    private ProductCollectionId $id;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductId")
     */
    private ProductId $productId;

    /**
     * @JMS\Type("DateTime")
     */
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
