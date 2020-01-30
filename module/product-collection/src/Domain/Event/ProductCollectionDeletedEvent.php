<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Domain\Event;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ProductCollectionDeletedEvent extends AbstractDeleteEvent
{
    /**
     * @var ProductCollectionId
     *
     * @JMS\Type("Ergonode\ProductCollection\Domain\Entity\ProductCollectionId")
     */
    private ProductCollectionId $id;

    /**
     * @param ProductCollectionId $id
     */
    public function __construct(ProductCollectionId $id)
    {
        $this->id = $id;
    }

    /**
     * @return AbstractId|ProductCollectionId
     */
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }
}
