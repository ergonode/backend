<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Domain\Entity;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\Category\Domain\Event\Tree\CategoryTreeCategoriesChangedEvent;
use Ergonode\Category\Domain\Event\Tree\CategoryTreeCategoryAddedEvent;
use Ergonode\Category\Domain\Event\Tree\CategoryTreeCreatedEvent;
use Ergonode\Category\Domain\Event\Tree\CategoryTreeNameChangedEvent;
use Ergonode\Category\Domain\ValueObject\Node;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Webmozart\Assert\Assert;
use JMS\Serializer\Annotation as JMS;

/**
 */
class CategoryTree extends AbstractAggregateRoot
{
    public const DEFAULT = 'Default';

    /**
     * @var CategoryTreeId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId");
     */
    private CategoryTreeId $id;

    /**
     * @var string
     *
     * @JMS\Type("string");
     */
    private string $code;

    /**
     * @var TranslatableString
     *
     * @JMS\Type(" Ergonode\Core\Domain\ValueObject\TranslatableString");
     */
    private TranslatableString $name;

    /**
     * @var Node[]
     *
     * @JMS\Type("array<Ergonode\Category\Domain\ValueObject\Node>");
     */
    private array $categories;

    /**
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
    public function getId(): CategoryTreeId
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
     * @param TranslatableString $name
     *
     * @throws \Exception
     */
    public function changeName(TranslatableString $name): void
    {
        if ($this->name->getTranslations() !== $name->getTranslations()) {
            $this->apply(new CategoryTreeNameChangedEvent($this->id, $this->name, $name));
        }
    }

    /**
     * @param CategoryId      $categoryId
     * @param CategoryId|null $parentId
     *
     * @throws \Exception
     */
    public function addCategory(CategoryId $categoryId, CategoryId $parentId = null): void
    {
        if ($this->hasCategory($categoryId)) {
            throw new \InvalidArgumentException(\sprintf('Category %s already exists', $categoryId->getValue()));
        }

        $this->apply(new CategoryTreeCategoryAddedEvent($this->id, $categoryId, $parentId));
    }

    /**
     * @param array $categories
     *
     * @throws \Exception
     */
    public function updateCategories(array $categories): void
    {
        Assert::allIsInstanceOf($categories, Node::class);

        $this->apply(new CategoryTreeCategoriesChangedEvent($this->id, $categories));
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
     * @return Node[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }


    /**
     * @param CategoryTreeCreatedEvent $event
     */
    protected function applyCategoryTreeCreatedEvent(CategoryTreeCreatedEvent $event): void
    {
        $this->categories = [];
        $this->id = $event->getAggregateId();
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
        $node = new Node($event->getCategoryId());
        if ($parent) {
            $parent->addChild($node);
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

        foreach ($node->getChildren() as $child) {
            $node = $this->findSingleNode($categoryId, $child);
            if ($node) {
                return $node;
            }
        }

        return null;
    }
}
