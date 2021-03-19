<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Domain\Event;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use JMS\Serializer\Annotation as JMS;

class ProductCollectionTypeNameChangedEvent implements AggregateEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId")
     */
    private ProductCollectionTypeId $id;

    private TranslatableString $to;

    /**
     * ProductCollectionTypeNameChangedEvent constructor.
     */
    public function __construct(ProductCollectionTypeId $id, TranslatableString $to)
    {
        $this->id = $id;
        $this->to = $to;
    }

    public function getTo(): TranslatableString
    {
        return $this->to;
    }

    public function getAggregateId(): ProductCollectionTypeId
    {
        return $this->id;
    }
}
