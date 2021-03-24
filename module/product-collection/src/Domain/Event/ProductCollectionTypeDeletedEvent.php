<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;

class ProductCollectionTypeDeletedEvent extends AbstractDeleteEvent
{
    private ProductCollectionTypeId $id;

    public function __construct(ProductCollectionTypeId $id)
    {
        $this->id = $id;
    }

    public function getAggregateId(): ProductCollectionTypeId
    {
        return $this->id;
    }
}
