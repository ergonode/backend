<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Domain\Event;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ProductCollectionTypeIdChangedEvent implements DomainEventInterface
{
    /**
     * @var ProductCollectionId
     *
     * @JMS\Type("Ergonode\ProductCollection\Domain\Entity\ProductCollectionId")
     */
    private ProductCollectionId $id;

    /**
     * @var ProductCollectionTypeId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId")
     */
    private ProductCollectionTypeId $oldTypeId;

    /**
     * @var ProductCollectionTypeId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId")
     */
    private ProductCollectionTypeId $newTypeId;

    /**
     * @var \DateTime
     *
     * @JMS\Type("DateTime")
     */
    private \DateTime $editedAt;

    /**
     * ProductCollectionTypeIdChangedEvent constructor.
     *
     * @param ProductCollectionId     $id
     * @param ProductCollectionTypeId $oldTypeId
     * @param ProductCollectionTypeId $newTypeId
     * @param \DateTime               $editedAt
     */
    public function __construct(
        ProductCollectionId $id,
        ProductCollectionTypeId $oldTypeId,
        ProductCollectionTypeId $newTypeId,
        \DateTime $editedAt
    ) {
        $this->id = $id;
        $this->oldTypeId = $oldTypeId;
        $this->newTypeId = $newTypeId;
        $this->editedAt = $editedAt;
    }

    /**
     * @return \DateTime
     */
    public function getEditedAt(): \DateTime
    {
        return $this->editedAt;
    }


    /**
     * @return AbstractID|ProductCollectionId
     */
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }

    /**
     * @return ProductCollectionTypeId
     */
    public function getOldTypeId(): ProductCollectionTypeId
    {
        return $this->oldTypeId;
    }

    /**
     * @return ProductCollectionTypeId
     */
    public function getNewTypeId(): ProductCollectionTypeId
    {
        return $this->newTypeId;
    }
}
