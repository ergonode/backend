<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Domain\Event;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use JMS\Serializer\Annotation as JMS;

class ProductCollectionDescriptionChangedEvent implements DomainEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId")
     */
    private ProductCollectionId $id;

    /**
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $to;

    /**
     * @JMS\Type("DateTime")
     */
    private \DateTime $editedAt;

    /**
     * ProductCollectionDescriptionChangedEvent constructor.
     */
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
