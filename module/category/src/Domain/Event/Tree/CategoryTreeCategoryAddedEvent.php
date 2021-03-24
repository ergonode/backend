<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Domain\Event\Tree;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;

class CategoryTreeCategoryAddedEvent implements AggregateEventInterface
{
    private CategoryTreeId $id;

    private CategoryId $categoryId;

    private ?CategoryId $parentId;

    public function __construct(CategoryTreeId $id, CategoryId $categoryId, ?CategoryId $parentId = null)
    {
        $this->id = $id;
        $this->categoryId = $categoryId;
        $this->parentId = $parentId;
    }

    public function getAggregateId(): CategoryTreeId
    {
        return $this->id;
    }

    public function getCategoryId(): CategoryId
    {
        return $this->categoryId;
    }

    public function getParentId(): ?CategoryId
    {
        return $this->parentId;
    }
}
