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
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionTypeId;
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
     * @JMS\Type("Ergonode\ProductCollection\Domain\Entity\ProductCollectionTypeId")
     */
    private ProductCollectionTypeId $typeId;

    /**
     * @var ProductCollectionTypeId
     *
     * @JMS\Type("Ergonode\ProductCollection\Domain\Entity\ProductCollectionTypeId")
     */
    private ProductCollectionTypeId $newTypeId;

    /**
     * ProductCollectionTypeIdChangedEvent constructor.
     *
     * @param ProductCollectionId     $id
     * @param ProductCollectionTypeId $typeId
     * @param ProductCollectionTypeId $newTypeId
     */
    public function __construct(
        ProductCollectionId $id,
        ProductCollectionTypeId $typeId,
        ProductCollectionTypeId $newTypeId
    ) {
        $this->id = $id;
        $this->typeId = $typeId;
        $this->newTypeId = $newTypeId;
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
    public function getTypeId(): ProductCollectionTypeId
    {
        return $this->typeId;
    }

    /**
     * @return ProductCollectionTypeId
     */
    public function getNewTypeId(): ProductCollectionTypeId
    {
        return $this->newTypeId;
    }
}
