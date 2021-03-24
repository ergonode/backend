<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Event;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;

class ProductAddedToCategoryEvent implements AggregateEventInterface
{
    private ProductId $id;

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
