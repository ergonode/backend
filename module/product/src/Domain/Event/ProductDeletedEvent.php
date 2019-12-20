<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Event;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;
use Ergonode\Product\Domain\Entity\ProductId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ProductDeletedEvent extends AbstractDeleteEvent
{
    /**
     * @var ProductId
     *
     * @JMS\Type("Ergonode\Product\Domain\Entity\ProductId")
     */
    private $id;

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
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }
}
