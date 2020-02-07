<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Domain\Event;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ProductCollectionElementRemovedEvent implements DomainEventInterface
{
    /**
     * @var ProductCollectionId
     *
     * @JMS\Type("Ergonode\ProductCollection\Domain\Entity\ProductCollectionId")
     */
    private ProductCollectionId $id;

    /**
     * @var ProductId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductId")
     */
    private ProductId $productId;

    /**
     * @var \DateTime
     *
     * @JMS\Type("DateTime")
     */
    private \DateTime $collectionEditedAt;

    /**
     * @param ProductCollectionId $id
     * @param ProductId           $productId
     * @param \DateTime           $collectionEditedAt
     */
    public function __construct(ProductCollectionId $id, ProductId $productId, \DateTime $collectionEditedAt)
    {
        $this->id = $id;
        $this->productId = $productId;
        $this->collectionEditedAt = $collectionEditedAt;
    }


    /**
     * @return AbstractId|ProductCollectionId
     */
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getCollectionEditedAt(): \DateTime
    {
        return $this->collectionEditedAt;
    }

    /**
     * @return ProductId
     */
    public function getProductId(): ProductId
    {
        return $this->productId;
    }
}
