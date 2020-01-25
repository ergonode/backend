<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Domain\Event;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionElementId;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ProductCollectionElementVisibleChangedEvent implements DomainEventInterface
{
    /**
     * @var ProductCollectionId
     *
     * @JMS\Type("Ergonode\ProductCollection\Domain\Entity\ProductCollectionId")
     */
    private ProductCollectionId $id;

    /**
     * @var ProductCollectionElementId
     *
     * @JMS\Type("Ergonode\ProductCollection\Domain\Entity\ProductCollectionElementId")
     */
    private ProductCollectionElementId $elementId;

    /**
     * @var bool
     *
     * @JMS\Type("bool")
     */
    private bool $newVisible;

    /**
     * ProductCollectionElementVisibleChangedEvent constructor.
     *
     * @param ProductCollectionId        $id
     * @param ProductCollectionElementId $elementId
     * @param bool                       $newVisible
     */
    public function __construct(ProductCollectionId $id, ProductCollectionElementId $elementId, bool $newVisible)
    {
        $this->id = $id;
        $this->elementId = $elementId;
        $this->newVisible = $newVisible;
    }

    /**
     * @return AbstractId|ProductCollectionId
     */
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }

    /**
     * @return ProductCollectionElementId
     */
    public function getElementId(): ProductCollectionElementId
    {
        return $this->elementId;
    }

    /**
     * @return bool
     */
    public function isNewVisible(): bool
    {
        return $this->newVisible;
    }
}
