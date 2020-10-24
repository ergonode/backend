<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use JMS\Serializer\Annotation as JMS;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;

class ProductAddedToCategoryEvent implements DomainEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductId")
     */
    private ProductId $id;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\CategoryId")
     */
    private CategoryId $categoryId;

    public function __construct(ProductId $id, CategoryId $categoryId)
    {
        $this->id = $id;
        $this->categoryId = $categoryId;
    }

    public function getAggregateId(): ProductId
    {
        return $this->id;
    }

    public function getCategoryId(): CategoryId
    {
        return $this->categoryId;
    }
}
