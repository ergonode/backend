<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Event;

use Ergonode\Category\Domain\ValueObject\CategoryCode;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ProductAddedToCategoryEvent implements DomainEventInterface
{
    /**
     * @var ProductId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductId")
     */
    private ProductId $id;

    /**
     * @var CategoryCode
     *
     * @JMS\Type("Ergonode\Category\Domain\ValueObject\CategoryCode")
     */
    private CategoryCode $categoryCode;

    /**
     * @param ProductId    $id
     * @param CategoryCode $categoryCode
     */
    public function __construct(ProductId $id, CategoryCode $categoryCode)
    {
        $this->id = $id;
        $this->categoryCode = $categoryCode;
    }

    /**
     * @return ProductId
     */
    public function getAggregateId(): ProductId
    {
        return $this->id;
    }

    /**
     * @return CategoryCode
     */
    public function getCategoryCode(): CategoryCode
    {
        return $this->categoryCode;
    }
}
