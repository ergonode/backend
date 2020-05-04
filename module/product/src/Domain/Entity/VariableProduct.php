<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Entity;

use Ergonode\Product\Domain\Event\GroupingProduct\ChildAddedToProductEvent;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\Event\GroupingProduct\ChildRemovedFromProductEvent;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Product\Domain\Event\Bind\BindAddedToProductEvent;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\Product\Domain\Event\Bind\BindRemovedFromProductEvent;

/**
 */
class VariableProduct extends AbstractProduct
{
    public const TYPE = 'VARIABLE-PRODUCT';

    /**
     * @var ProductId[]
     *
     * @JMS\Type("array<Ergonode\SharedKernel\Domain\Aggregate\ProductId>");
     */
    private array $children = [];

    /**
     * @var AttributeId[]
     *
     * @JMS\Type("array<Ergonode\SharedKernel\Domain\Aggregate\AttributeId>");
     */
    private array $bindings = [];

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
     * @param AbstractProduct $child
     *
     * @throws \Exception
     */
    public function addChild(AbstractProduct $child): void
    {
        if (!$this->hasChild($child->getId())) {
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
     * @param SelectAttribute $attribute
     *
     * @throws \Exception
     */
    public function addBind(SelectAttribute $attribute): void
    {
        if (!$this->hasBinding($attribute->getId())) {
            $this->apply(new BindAddedToProductEvent($this->id, $attribute->getId()));
        }
    }

    /**
     * @param AttributeId $attributeId
     *
     * @throws \Exception
     */
    public function removeBind(AttributeId $attributeId): void
    {
        if ($this->hasBinding($attributeId)) {
            $this->apply(new BindRemovedFromProductEvent($this->id, $attributeId));
        }
    }

    /**
     * @param AttributeId $bindId
     *
     * @return bool
     */
    public function hasBinding(AttributeId $bindId): bool
    {
        foreach ($this->bindings as $bind) {
            if ($bindId->isEqual($bind)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return ProductId[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @return AttributeId[]
     */
    public function getBindings(): array
    {
        return $this->bindings;
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

    /**
     * @param BindAddedToProductEvent $event
     */
    protected function applyBindAddedToProductEvent(BindAddedToProductEvent $event): void
    {
        $this->bindings[] = $event->getAttributeId();
    }

    /**
     * @param BindRemovedFromProductEvent $event
     */
    protected function applyBindRemovedFromProductEvent(BindRemovedFromProductEvent $event): void
    {
        foreach ($this->bindings as $key => $binding) {
            if ($binding->isEqual($event->getAttributeId())) {
                unset($this->children[$key]);
            }
        }
    }
}
