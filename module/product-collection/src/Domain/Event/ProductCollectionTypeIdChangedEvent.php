<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Domain\Event;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;

class ProductCollectionTypeIdChangedEvent implements AggregateEventInterface
{
    private ProductCollectionId $id;

    private ProductCollectionTypeId $newTypeId;

    private \DateTime $editedAt;

    public function __construct(
        ProductCollectionId $id,
        ProductCollectionTypeId $newTypeId,
        \DateTime $editedAt
    ) {
        $this->id = $id;
        $this->newTypeId = $newTypeId;
        $this->editedAt = $editedAt;
    }

    public function getEditedAt(): \DateTime
    {
        return $this->editedAt;
    }


    public function getAggregateId(): ProductCollectionId
    {
        return $this->id;
    }

    public function getNewTypeId(): ProductCollectionTypeId
    {
        return $this->newTypeId;
    }
}
