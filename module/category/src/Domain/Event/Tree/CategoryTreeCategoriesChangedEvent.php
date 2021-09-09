<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Domain\Event\Tree;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Ergonode\Category\Domain\ValueObject\Node;
use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Webmozart\Assert\Assert;

class CategoryTreeCategoriesChangedEvent implements AggregateEventInterface
{
    private CategoryTreeId $id;

    /**
     * @var Node[]
     */
    private array $categories;

    /**
     * @param Node[] $categories
     */
    public function __construct(CategoryTreeId $id, array $categories = [])
    {
        Assert::allIsInstanceOf($categories, Node::class);
        $this->id = $id;
        $this->categories = $categories;
    }

    public function getAggregateId(): CategoryTreeId
    {
        return $this->id;
    }

    /**
     * @return Node[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }
}
