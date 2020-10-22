<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Entity;

use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use JMS\Serializer\Annotation as JMS;
use Ergonode\Product\Domain\Event\Relation\ChildAddedToProductEvent;
use Ergonode\Product\Domain\Event\Relation\ChildRemovedFromProductEvent;
use Webmozart\Assert\Assert;

abstract class AbstractAssociatedProduct extends AbstractProduct
{
    /**
     * @var ProductId[]
     *
     * @JMS\Type("array<Ergonode\SharedKernel\Domain\Aggregate\ProductId>");
     */
    private array $children = [];

    /**
     * @param AbstractProduct $child
     *
     * @throws \Exception
     */
    public function addChild(AbstractProduct $child): void
    {
        if (!$this->hasChild($child->getId()) && !$child->getId()->isEqual($this->id)) {
            $this->apply(new ChildAddedToProductEvent($this->id, $child->getId()));
        }
    }

    /**
     * @param ProductId $childId
     *
     * @throws \Exception
     */
    public function removeChild(ProductId $childId): void
    {
        if (false !== $this->hasChild($childId)) {
            $this->apply(new ChildRemovedFromProductEvent($this->id, $childId));
        }
    }

    /**
     * @param ProductId $productId
     *
     * @return bool
     */
    public function hasChild(ProductId $productId): bool
    {
        foreach ($this->children as $child) {
            if ($productId->isEqual($child)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param AbstractProduct[] $children
     *
     * @throws \Exception
     */
    public function changeChildren(array $children): void
    {
        Assert::allIsInstanceOf($children, AbstractProduct::class);

        foreach ($children as $child) {
            if (!$this->hasChild($child->getId()) && !$child->getId()->isEqual($this->id)) {
                $this->addChild($child);
            }
        }

        foreach ($this->children as $child) {
            if (!in_array($child, $children, false)) {
                $this->removeChild($child);
            }
        }
    }

    /**
     * @return ProductId[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @param ChildAddedToProductEvent $event
     */
    protected function applyChildAddedToProductEvent(ChildAddedToProductEvent $event): void
    {
        $this->children[] = $event->getChildId();
    }

    /**
     * @param ChildRemovedFromProductEvent $event
     */
    protected function applyChildRemovedFromProductEvent(ChildRemovedFromProductEvent $event): void
    {
        foreach ($this->children as $key => $child) {
            if ($child->isEqual($event->getChildId())) {
                unset($this->children[$key]);
            }
        }
    }
}
