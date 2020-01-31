<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Domain\Event;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionElement;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ProductCollectionElementAddedEvent implements DomainEventInterface
{
    /**
     * @var ProductCollectionId
     *
     * @JMS\Type("Ergonode\ProductCollection\Domain\Entity\ProductCollectionId")
     */
    private ProductCollectionId $id;

    /**
     * @var ProductCollectionElement
     *
     * @JMS\Type("Ergonode\ProductCollection\Domain\Entity\ProductCollectionElement")
     */
    private ProductCollectionElement $element;

    /**
     * @param ProductCollectionId      $id
     * @param ProductCollectionElement $element
     */
    public function __construct(ProductCollectionId $id, ProductCollectionElement $element)
    {
        $this->id = $id;
        $this->element = $element;
    }

    /**
     * @return AbstractId|ProductCollectionId
     */
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }

    /**
     * @return ProductCollectionElement
     */
    public function getElement(): ProductCollectionElement
    {
        return $this->element;
    }
}
