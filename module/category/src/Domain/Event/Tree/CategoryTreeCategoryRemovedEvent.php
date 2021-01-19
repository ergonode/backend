<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Domain\Event\Tree;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use JMS\Serializer\Annotation as JMS;

class CategoryTreeCategoryRemovedEvent implements AggregateEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId")
     */
    private CategoryTreeId $id;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\CategoryId")
     */
    private CategoryId $categoryId;

    public function __construct(CategoryTreeId $id, CategoryId $categoryId)
    {
        $this->id = $id;
        $this->categoryId = $categoryId;
    }

    public function getAggregateId(): CategoryTreeId
    {
        return $this->id;
    }
    public function getCategoryId(): CategoryId
    {
        return $this->categoryId;
    }
}
