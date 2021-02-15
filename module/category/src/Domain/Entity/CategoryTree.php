<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

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

class CategoryTree extends AbstractAggregateRoot
{
    public const DEFAULT = 'Default';

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId");
     */
    private CategoryTreeId $id;

    /**
     * @JMS\Type("string");
     */
    private string $code;

    /**
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
     * @throws \Exception
     */
    public function __construct(CategoryTreeId $id, string $code, TranslatableString $name)
    {
        $this->apply(new CategoryTreeCreatedEvent($id, $code, $name));
    }

    public function getId(): CategoryTreeId
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): TranslatableString
    {
        return $this->name;
    }

    /**
     * @throws \Exception
     */
    public function changeName(TranslatableString $name): void
    {
        if ($this->name->getTranslations() !== $name->getTranslations()) {
            $this->apply(new CategoryTreeNameChangedEvent($this->id, $name));
        }
    }

    /**
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

    protected function applyCategoryTreeCreatedEvent(CategoryTreeCreatedEvent $event): void
    {
        $this->categories = [];
        $this->id = $event->getAggregateId();
        $this->code = $event->getCode();
        $this->name = $event->getName();
    }

    protected function applyCategoryTreeNameChangedEvent(CategoryTreeNameChangedEvent $event): void
    {
        $this->name = $event->getTo();
    }

    protected function applyCategoryTreeCategoriesChangedEvent(CategoryTreeCategoriesChangedEvent $event): void
    {
        $this->categories = $event->getCategories();
    }

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
