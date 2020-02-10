<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ProductCollectionDeletedEvent extends AbstractDeleteEvent
{
    /**
     * @var ProductCollectionId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId")
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
     * @return ProductCollectionId
     */
    public function getAggregateId(): ProductCollectionId
    {
        return $this->id;
    }
}
