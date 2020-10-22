<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionElement;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use JMS\Serializer\Annotation as JMS;

class ProductCollectionElementAddedEvent implements DomainEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId")
     */
    private ProductCollectionId $id;

    /**
     * @JMS\Type("Ergonode\ProductCollection\Domain\Entity\ProductCollectionElement")
     */
    private ProductCollectionElement $element;

    /**
     * @JMS\Type("DateTime")
     */
    private \DateTime $currentDateTime;

    public function __construct(ProductCollectionId $id, ProductCollectionElement $element, \DateTime $currentDateTime)
    {
        $this->id = $id;
        $this->element = $element;
        $this->currentDateTime = $currentDateTime;
    }

    public function getCurrentDateTime(): \DateTime
    {
        return $this->currentDateTime;
    }

    public function getAggregateId(): ProductCollectionId
    {
        return $this->id;
    }

    public function getElement(): ProductCollectionElement
    {
        return $this->element;
    }
}
