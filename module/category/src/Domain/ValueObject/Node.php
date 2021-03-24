<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Domain\ValueObject;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;

class Node
{
    private CategoryId $categoryId;

    /**
     * @var Node[]
     */
    private array $children;

    public function __construct(CategoryId $categoryId)
    {
        $this->categoryId = $categoryId;
        $this->children = [];
    }

    public function addChild(Node $child): void
    {
        $this->children[] = $child;
    }

    public function getCategoryId(): CategoryId
    {
        return $this->categoryId;
    }

    public function hasChild(CategoryId $categoryId): bool
    {
        foreach ($this->children as $child) {
            if ($child->categoryId->isEqual($categoryId)) {
                return true;
            }
        }

        return false;
    }

    public function hasSuccessor(CategoryId $categoryId): bool
    {
        foreach ($this->children as $child) {
            if ($child->categoryId->isEqual($categoryId)) {
                return true;
            }

            if ($child->hasSuccessor($categoryId)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Node[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }
}
