<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Event;

use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Product\Domain\Entity\ProductId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ProductRemovedFromCategoryEvent implements DomainEventInterface
{
    /**
     * @var ProductId
     *
     * @JMS\Type("Ergonode\Product\Domain\Entity\ProductId")
     */
    private $id;

    /**
     * @var CategoryCode
     *
     * @JMS\Type("Ergonode\Category\Domain\ValueObject\CategoryCode")
     */
    private $categoryCode;

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
    public function getAggregateId(): AbstractId
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
