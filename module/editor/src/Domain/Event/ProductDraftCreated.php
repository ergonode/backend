<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Editor\Domain\Entity\ProductDraftId;
use Ergonode\Product\Domain\Entity\ProductId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ProductDraftCreated implements DomainEventInterface
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
    public function getId(): ProductDraftId
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
