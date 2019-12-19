<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Domain\Event;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Editor\Domain\Entity\ProductDraftId;
use Ergonode\EventSourcing\Infrastructure\DomainAggregateEventInterface;
use Ergonode\Product\Domain\Entity\ProductId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ProductDraftCreated implements DomainAggregateEventInterface
{
    /**
     * @var ProductDraftId
     *
     * @JMS\Type("Ergonode\Editor\Domain\Entity\ProductDraftId")
     */
    private $id;

    /**
     * @var ProductId
     *
     * @JMS\Type("Ergonode\Product\Domain\Entity\ProductId")
     */
    private $productId;

    /**
     * @param ProductDraftId $id
     * @param ProductId      $productId
     */
    public function __construct(ProductDraftId $id, ProductId $productId)
    {
        $this->id = $id;
        $this->productId = $productId;
    }

    /**
     * @return ProductDraftId
     */
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }

    /**
     * @return ProductId
     */
    public function getProductId(): ProductId
    {
        return $this->productId;
    }
}
