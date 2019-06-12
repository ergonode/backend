<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Domain\Entity;

use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\CategoryTree\Domain\Event\CategoryTreeCategoryAddedEvent;
use Ergonode\CategoryTree\Domain\Event\CategoryTreeCreatedEvent;

/**
 */
class CategoryTree extends AbstractAggregateRoot
{
    public const DEFAULT = 'Default';

    /**
     * @var CategoryTreeId
     */
    private $id;

    /**
     * @var CategoryId[]
     */
    private $categories;

    /**
     * @param CategoryTreeId  $id
     * @param string          $name
     * @param CategoryId|null $categoryId
     */
    public function __construct(CategoryTreeId $id, string $name, ?CategoryId $categoryId = null)
    {
        $this->apply(new CategoryTreeCreatedEvent($id, $name, $categoryId));
    }

    /**
     * @return CategoryTreeId
     */
    public function getId(): AbstractId
    {
        return $this->id;
    }

    /**
     * @param CategoryId $categoryId
     * @param CategoryId $parentId
     */
    public function addCategory(CategoryId $categoryId, CategoryId $parentId = null): void
    {
        if ($this->hasCategory($categoryId)) {
            throw new \InvalidArgumentException(\sprintf('Category %s already exists', $categoryId->getValue()));
        }

        $this->apply(new CategoryTreeCategoryAddedEvent($categoryId, $parentId));
    }

    /**
     * @param CategoryId $categoryId
     *
     * @return bool
     */
    public function hasCategory(CategoryId $categoryId): bool
    {
        return array_key_exists($categoryId->getValue(), $this->categories);
    }

    /**
     * @param CategoryId $categoryId
     *
     * @return CategoryId|null
     */
    public function getParent(CategoryId $categoryId): ?CategoryId
    {
        if ($this->hasCategory($categoryId)) {
            if ($this->categories[$categoryId->getValue()] !== null) {
                return new CategoryId($this->categories[$categoryId->getValue()]);
            }

            return null;
        }

        throw new \InvalidArgumentException(sprintf('Category %s not exits in tree', $categoryId->getValue()));
    }

    /**
     * @param CategoryTreeCreatedEvent $event
     */
    protected function applyCategoryTreeCreatedEvent(CategoryTreeCreatedEvent $event): void
    {
        $this->categories = [];
        $this->id = $event->getId();
        if ($event->getCategoryId()) {
            $this->categories[$event->getCategoryId()->getValue()] = null;
        }
    }

    /**
     * @param CategoryTreeCategoryAddedEvent $event
     */
    protected function applyCategoryTreeCategoryAddedEvent(CategoryTreeCategoryAddedEvent $event): void
    {
        if ($event->getParentId()) {
            $this->categories[$event->getId()->getValue()] = $event->getParentId()->getValue();
        } else {
            $this->categories[$event->getId()->getValue()] = null;
        }
    }
}
