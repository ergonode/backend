<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Domain\Entity;

use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\CategoryTree\Domain\Event\CategoryTreeCategoriesChangedEvent;
use Ergonode\CategoryTree\Domain\Event\CategoryTreeCategoryAddedEvent;
use Ergonode\CategoryTree\Domain\Event\CategoryTreeCreatedEvent;
use Ergonode\CategoryTree\Domain\Event\CategoryTreeNameChangedEvent;
use Ergonode\CategoryTree\Domain\ValueObject\Node;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Webmozart\Assert\Assert;

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
     * @var string
     */
    private $code;

    /**
     * @var TranslatableString
     */
    private $name;

    /**
     * @var Node[]
     */
    private $categories;

    /**
     * CategoryTree constructor.
     *
     * @param CategoryTreeId     $id
     * @param string             $code
     * @param TranslatableString $name
     *
     * @throws \Exception
     */
    public function __construct(CategoryTreeId $id, string $code, TranslatableString $name)
    {
        $this->apply(new CategoryTreeCreatedEvent($id, $code, $name));
    }

    /**
     * @return CategoryTreeId
     */
    public function getId(): AbstractId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return TranslatableString
     */
    public function getName(): TranslatableString
    {
        return $this->name;
    }

    /**
     * @param TranslatableString $title
     */
    public function changeName(TranslatableString $title): void
    {
        if ($this->name->getTranslations() !== $title->getTranslations()) {
            $this->apply(new CategoryTreeNameChangedEvent($this->name, $title));
        }
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
     * @param Node[] $categories
     */
    public function updateCategories(array $categories): void
    {
        Assert::allIsInstanceOf($categories, Node::class);

        $this->apply(new CategoryTreeCategoriesChangedEvent($categories));
    }

    /**
     * @param CategoryId $categoryId
     *
     * @return bool
     */
    public function hasCategory(CategoryId $categoryId): bool
    {
        foreach ($this->categories as $category) {
            if ($category->getCategoryId()->isEqual($categoryId)) {
                return true;
            }
            if ($category->hasSuccessor($categoryId)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param CategoryTreeCreatedEvent $event
     */
    protected function applyCategoryTreeCreatedEvent(CategoryTreeCreatedEvent $event): void
    {
        $this->categories = [];
        $this->id = $event->getId();
        $this->code = $event->getCode();
        $this->name = $event->getName();
    }

    /**
     * @param CategoryTreeNameChangedEvent $event
     */
    protected function applyCategoryTreeNameChangedEvent(CategoryTreeNameChangedEvent $event): void
    {
        $this->name = $event->getTo();
    }

    /**
     * @param CategoryTreeCategoriesChangedEvent $event
     */
    protected function applyCategoryTreeCategoriesChangedEvent(CategoryTreeCategoriesChangedEvent $event): void
    {
        $this->categories = $event->getCategories();
    }

    /**
     * @param CategoryTreeCategoryAddedEvent $event
     */
    protected function applyCategoryTreeCategoryAddedEvent(CategoryTreeCategoryAddedEvent $event): void
    {
        $parent = $event->getParentId() ? $this->findNode($event->getParentId()) : null;
        $node = new Node($event->getId());
        if ($parent) {
            $parent->addChildren($node);
        } else {
            $this->categories[] = $node;
        }
    }

    /**
     * @param CategoryId $categoryId
     *
     * @return Node|null
     */
    private function findNode(CategoryId $categoryId): ?Node
    {
        foreach ($this->categories as $category) {
            $node = $this->findSingleNode($categoryId, $category);
            if ($node) {
                return $node;
            }
        }

        return null;
    }

    /**
     * @param CategoryId $categoryId
     * @param Node       $node
     *
     * @return Node|null
     */
    private function findSingleNode(CategoryId $categoryId, Node $node): ?Node
    {
        if ($node->getCategoryId()->isEqual($categoryId)) {
            return $node;
        }

        foreach ($node->getChildrens() as $children) {
            $node = $this->findSingleNode($categoryId, $children);
            if ($node) {
                return $node;
            }
        }

        return null;
    }
}
