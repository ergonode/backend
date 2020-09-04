<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Entity;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Product\Domain\Event\Bind\BindAddedToProductEvent;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\Product\Domain\Event\Bind\BindRemovedFromProductEvent;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;

/**
 */
class VariableProduct extends AbstractAssociatedProduct
{
    public const TYPE = 'VARIABLE-PRODUCT';

    /**
     * @var AttributeId[]
     *
     * @JMS\Type("array<Ergonode\SharedKernel\Domain\Aggregate\AttributeId>");
     */
    private array $bindings = [];

    /**
     * @JMS\Type("string");
     *
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @param SelectAttribute $attribute
     *
     * @throws \Exception
     */
    public function addBind(SelectAttribute $attribute): void
    {
        if (!$this->hasBind($attribute->getId())) {
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
        if ($this->hasBind($attributeId)) {
            $this->apply(new BindRemovedFromProductEvent($this->id, $attributeId));
        }
    }

    /**
     * @param AttributeId $bindId
     *
     * @return bool
     */
    public function hasBind(AttributeId $bindId): bool
    {
        foreach ($this->bindings as $bind) {
            if ($bindId->isEqual($bind)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param SelectAttribute[] $bindings
     *
     * @throws \Exception
     */
    public function changeBindings(array $bindings): void
    {
        Assert::allIsInstanceOf($bindings, SelectAttribute::class);

        foreach ($bindings as $binding) {
            if (!$this->hasBind($binding->getId())) {
                $this->addBind($binding);
            }
        }

        foreach ($this->bindings as $binding) {
            if (!in_array($binding, $this->bindings, false)) {
                $this->removeBind($binding);
            }
        }
    }

    /**
     * @return AttributeId[]
     */
    public function getBindings(): array
    {
        return $this->bindings;
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
                unset($this->bindings[$key]);
            }
        }
    }
}
