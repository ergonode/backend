<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Entity;

use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\Event\Child\ChildAddedToProductEvent;
use Ergonode\Product\Domain\Event\Child\ChildRemovedFromProductEvent;

/**
 */
class GroupingProduct extends AbstractProduct
{
    public const TYPE = 'GROUPING-PRODUCT';

    /**
     * @var ProductId[]
     *
     * @JMS\Type("array<Ergonode\SharedKernel\Domain\Aggregate\ProductId>");
     */
    private array $children;

    /**
     * @JMS\virtualProperty();
     * @JMS\SerializedName("type")
     *
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @param SimpleProduct $child
     *
     * @throws \Exception
     */
    public function addChild(SimpleProduct $child): void
    {
        if (false === $this->hasChild($child->getId())) {
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
     * @return array|ProductId[]
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
        $this->children[] = $event->getChildId();
    }
}
