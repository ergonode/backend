<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Editor\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\ProductDraftId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use JMS\Serializer\Annotation as JMS;

class ProductDraftCreated implements DomainEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductDraftId")
     */
    private ProductDraftId $id;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductId")
     */
    private ProductId $productId;

    public function __construct(ProductDraftId $id, ProductId $productId)
    {
        $this->id = $id;
        $this->productId = $productId;
    }

    public function getAggregateId(): ProductDraftId
    {
        return $this->id;
    }

    public function getProductId(): ProductId
    {
        return $this->productId;
    }
}
