<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class CategoryTreeCategoryRemovedEvent implements DomainEventInterface
{
    /**
     * @var CategoryTreeId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId")
     */
    private $id;

    /**
     * @var CategoryId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\CategoryId")
     */
    private $categoryId;

    /**
     * @param CategoryTreeId $id
     * @param CategoryId     $categoryId
     */
    public function __construct(CategoryTreeId $id, CategoryId $categoryId)
    {
        $this->id = $id;
        $this->categoryId = $categoryId;
    }

    /**
     * @return CategoryTreeId
     */
    public function getAggregateId(): CategoryTreeId
    {
        return $this->id;
    }
    /**
     * @return CategoryId
     */
    public function getCategoryId(): CategoryId
    {
        return $this->categoryId;
    }
}
