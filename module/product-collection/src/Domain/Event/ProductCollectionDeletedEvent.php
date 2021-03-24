<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;

class ProductCollectionDeletedEvent extends AbstractDeleteEvent
{
    private ProductCollectionId $id;

    public function __construct(ProductCollectionId $id)
    {
        $this->id = $id;
    }

    public function getAggregateId(): ProductCollectionId
    {
        return $this->id;
    }
}
