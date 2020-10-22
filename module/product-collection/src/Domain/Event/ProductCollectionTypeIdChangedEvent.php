<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use JMS\Serializer\Annotation as JMS;

class ProductCollectionTypeIdChangedEvent implements DomainEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId")
     */
    private ProductCollectionId $id;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId")
     */
    private ProductCollectionTypeId $oldTypeId;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId")
     */
    private ProductCollectionTypeId $newTypeId;

    /**
     * @JMS\Type("DateTime")
     */
    private \DateTime $editedAt;

    /**
     * ProductCollectionTypeIdChangedEvent constructor.
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

    public function getEditedAt(): \DateTime
    {
        return $this->editedAt;
    }


    public function getAggregateId(): ProductCollectionId
    {
        return $this->id;
    }

    public function getOldTypeId(): ProductCollectionTypeId
    {
        return $this->oldTypeId;
    }

    public function getNewTypeId(): ProductCollectionTypeId
    {
        return $this->newTypeId;
    }
}
