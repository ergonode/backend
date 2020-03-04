<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Domain\ValueObject;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class Node
{
    /**
     * @var null|Node
     *
     * @JMS\Exclude()
     */
    private ?Node $parent;

    /**
     * @var CategoryId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\CategoryId")
     */
    private CategoryId $categoryId;

    /**
     * @var Node[];
     *
     * @JMS\Type("array<Ergonode\Category\Domain\ValueObject\Node>")
     */
    private array $childrens;

    /**
     * @param CategoryId $categoryId
     */
    public function __construct(CategoryId $categoryId)
    {
        $this->categoryId = $categoryId;
        $this->childrens = [];
    }

    /**
     * @param Node $children
     */
    public function addChildren(Node $children): void
    {
        $this->childrens[] = $children;
        $children->setParent($this);
    }

    /**
     * @param Node|null $parent
     */
    public function setParent(?Node $parent = null): void
    {
        $this->parent = $parent;
    }

    /**
     * @return Node|null
     */
    public function getParent(): ?Node
    {
        return $this->parent;
    }

    /**
     * @return CategoryId
     */
    public function getCategoryId(): CategoryId
    {
        return $this->categoryId;
    }

    /**
     * @param CategoryId $categoryId
     *
     * @return bool
     */
    public function hasChildren(CategoryId $categoryId): bool
    {
        foreach ($this->childrens as $children) {
            if ($children->categoryId->isEqual($categoryId)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param CategoryId $categoryId
     *
     * @return bool
     */
    public function hasSuccessor(CategoryId $categoryId): bool
    {
        foreach ($this->childrens as $children) {
            if ($children->categoryId->isEqual($categoryId)) {
                return true;
            }

            return $children->hasSuccessor($categoryId);
        }

        return false;
    }

    /**
     * @return Node[]
     */
    public function getChildrens(): array
    {
        return $this->childrens;
    }
}
