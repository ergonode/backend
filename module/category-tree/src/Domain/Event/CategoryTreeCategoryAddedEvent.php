<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Domain\Event;

use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class CategoryTreeCategoryAddedEvent implements DomainEventInterface
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
     * @JMS\Type("Ergonode\Category\Domain\Entity\CategoryId")
     */
    private $categoryId;

    /**
     * @var CategoryId|null
     *
     * @JMS\Type("Ergonode\Category\Domain\Entity\CategoryId")
     */
    private $parentId;

    /**
     * @param CategoryTreeId  $id
     * @param CategoryId      $categoryId
     * @param CategoryId|null $parentId
     */
    public function __construct(CategoryTreeId $id, CategoryId $categoryId, ?CategoryId $parentId = null)
    {
        $this->id = $id;
        $this->categoryId = $categoryId;
        $this->parentId = $parentId;
    }

    /**
     * @return AbstractId|CategoryTreeId
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

    /**
     * @return CategoryId|null
     */
    public function getParentId(): ?CategoryId
    {
        return $this->parentId;
    }
}
