<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Domain\Event;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionElement;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;

class ProductCollectionElementAddedEvent implements AggregateEventInterface
{
    private ProductCollectionId $id;

    private ProductCollectionElement $element;

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
