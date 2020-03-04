<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ProductDeletedEvent extends AbstractDeleteEvent
{
    /**
     * @var ProductId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductId")
     */
    private ProductId $id;

    /**
     * @param ProductId $id
     */
    public function __construct(ProductId $id)
    {
        $this->id = $id;
    }

    /**
     * @return ProductId
     */
    public function getAggregateId(): ProductId
    {
        return $this->id;
    }
}
