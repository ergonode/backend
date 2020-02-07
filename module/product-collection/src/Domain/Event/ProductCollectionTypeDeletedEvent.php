<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Domain\Event;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;

/**
 */
class ProductCollectionTypeDeletedEvent extends AbstractDeleteEvent
{
    /**
     * @var ProductCollectionTypeId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId")
     */
    private ProductCollectionTypeId $id;

    /**
     * @param ProductCollectionTypeId $id
     */
    public function __construct(ProductCollectionTypeId $id)
    {
        $this->id = $id;
    }

    /**
     * @return ProductCollectionTypeId
     */
    public function getAggregateId(): ProductCollectionTypeId
    {
        return $this->id;
    }
}
