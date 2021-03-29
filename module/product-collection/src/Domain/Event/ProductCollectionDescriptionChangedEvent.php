<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Domain\Event;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;

class ProductCollectionDescriptionChangedEvent implements AggregateEventInterface
{
    private ProductCollectionId $id;

    private TranslatableString $to;

    private \DateTime $editedAt;

    public function __construct(
        ProductCollectionId $id,
        TranslatableString $to,
        \DateTime $editedAt
    ) {
        $this->id = $id;
        $this->to = $to;
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

    public function getTo(): TranslatableString
    {
        return $this->to;
    }
}
