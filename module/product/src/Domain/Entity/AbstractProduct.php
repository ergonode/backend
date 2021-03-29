<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Entity;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Product\Domain\Event\ProductAddedToCategoryEvent;
use Ergonode\Product\Domain\Event\ProductCreatedEvent;
use Ergonode\Product\Domain\Event\ProductRemovedFromCategoryEvent;
use Ergonode\Product\Domain\Event\ProductValueAddedEvent;
use Ergonode\Product\Domain\Event\ProductValueChangedEvent;
use Ergonode\Product\Domain\Event\ProductValueRemovedEvent;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Webmozart\Assert\Assert;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\Product\Domain\Event\ProductTemplateChangedEvent;

abstract class AbstractProduct extends AbstractAggregateRoot implements ProductInterface
{
    protected ProductId $id;

    protected Sku $sku;

    /**
     * @var ValueInterface[]
     */
    protected array $attributes;

    /**
     * @var CategoryId[]
     */
    protected array $categories;

    protected TemplateId $templateId;

    /**
     * @param CategoryId[] $categories
     * @param array        $attributes
     *
     * @throws \Exception
     */
    public function __construct(
        ProductId $id,
        Sku $sku,
        TemplateId $templateId,
        array $categories = [],
        array $attributes = []
    ) {
        Assert::allIsInstanceOf($categories, CategoryId::class);

        $this->apply(new ProductCreatedEvent(
            $id,
            $sku,
            $this->getType(),
            $templateId,
        ));

        foreach ($categories as $categoryId) {
            $this->addToCategory($categoryId);
        }

        foreach (array_filter($attributes) as $code => $value) {
            $this->addAttribute(new AttributeCode($code), $value);
        }
    }

    abstract public function getType(): string;

    public function getId(): ProductId
    {
        return $this->id;
    }

    public function getSku(): Sku
    {
        return $this->sku;
    }

    /**
     * @throws \Exception
     */
    public function changeTemplate(TemplateId $templateId): void
    {
        if (!$templateId->isEqual($this->templateId)) {
            $this->apply(new ProductTemplateChangedEvent($this->id, $templateId));
        }
    }

    public function belongToCategory(CategoryId $categoryId): bool
    {
        foreach ($this->categories as $category) {
            if ($categoryId->isEqual($category)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @throws \Exception
     */
    public function addToCategory(CategoryId $categoryId): void
    {
        if (!$this->belongToCategory($categoryId)) {
            $this->apply(new ProductAddedToCategoryEvent($this->id, $categoryId));
        }
    }

    /**
     * @throws \Exception
     */
    public function removeFromCategory(CategoryId $categoryId): void
    {
        if ($this->belongToCategory($categoryId)) {
            $this->apply(new ProductRemovedFromCategoryEvent($this->id, $categoryId));
        }
    }

    /**
     * @param CategoryId[] $categories
     *
     * @throws \Exception
     */
    public function changeCategories(array $categories): void
    {
        Assert::allIsInstanceOf($categories, CategoryId::class);

        foreach ($categories as $categoryId) {
            if (!$this->belongToCategory($categoryId)) {
                $this->addToCategory($categoryId);
            }
        }

        foreach ($this->categories as $categoryId) {
            if (!in_array($categoryId, $categories, false)) {
                $this->removeFromCategory($categoryId);
            }
        }
    }

    /**
     * @return CategoryId[]
     */
    public function getCategories(): array
    {
        return array_values($this->categories);
    }

    public function hasAttribute(AttributeCode $attributeCode): bool
    {
        return isset($this->attributes[$attributeCode->getValue()]);
    }

    public function getAttribute(AttributeCode $attributeCode): ValueInterface
    {
        if (!$this->hasAttribute($attributeCode)) {
            throw new \RuntimeException(sprintf('Value for attribute %s not exists', $attributeCode->getValue()));
        }

        return clone $this->attributes[$attributeCode->getValue()];
    }

    /**
     * @throws \Exception
     */
    public function addAttribute(AttributeCode $attributeCode, ValueInterface $value): void
    {
        if ($this->hasAttribute($attributeCode)) {
            throw new \RuntimeException('Value already exists');
        }

        $this->apply(new ProductValueAddedEvent($this->id, $attributeCode, $value));
    }

    /**
     * @param ValueInterface[] $attributes
     *
     * @throws \Exception
     */
    public function changeAttributes(array $attributes): void
    {
        Assert::allString(array_keys($attributes));
        Assert::allIsInstanceOf($attributes, ValueInterface::class);

        foreach ($attributes as $code => $attribute) {
            $attributeCode = new AttributeCode($code);
            if ($this->hasAttribute($attributeCode)) {
                $this->changeAttribute($attributeCode, $attribute);
            } else {
                $this->addAttribute($attributeCode, $attribute);
            }
        }

        foreach (array_keys($this->attributes) as $code) {
            $attributeCode = new AttributeCode($code);
            if (!array_key_exists($code, $attributes)) {
                $this->removeAttribute($attributeCode);
            }
        }
    }

    /**
     * @return ValueInterface[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @throws \Exception
     */
    public function changeAttribute(AttributeCode $attributeCode, ValueInterface $value): void
    {
        if (!$this->hasAttribute($attributeCode)) {
            throw new \RuntimeException('Value note exists');
        }

        if (!$value->isEqual($this->attributes[$attributeCode->getValue()])) {
            $this->apply(
                new ProductValueChangedEvent(
                    $this->id,
                    $attributeCode,
                    $this->attributes[$attributeCode->getValue()],
                    $value
                )
            );
        }
    }

    /**
     * @throws \Exception
     */
    public function removeAttribute(AttributeCode $attributeCode): void
    {
        if (!$this->hasAttribute($attributeCode)) {
            throw new \RuntimeException('Value note exists');
        }

        $this->apply(
            new ProductValueRemovedEvent($this->id, $attributeCode)
        );
    }

    public function getTemplateId(): TemplateId
    {
        return $this->templateId;
    }

    protected function applyProductCreatedEvent(ProductCreatedEvent $event): void
    {
        $this->id = $event->getAggregateId();
        $this->sku = $event->getSku();
        $this->attributes = [];
        $this->categories = [];
        $this->templateId = $event->getTemplateId();
        foreach ($event->getCategories() as $category) {
            $this->categories[] = $category;
        }
        foreach ($event->getAttributes() as $key => $attribute) {
            $this->attributes[$key] = $attribute;
        }
    }

    protected function applyProductAddedToCategoryEvent(ProductAddedToCategoryEvent $event): void
    {
        $this->categories[] = $event->getCategoryId();
    }

    protected function applyProductRemovedFromCategoryEvent(ProductRemovedFromCategoryEvent $event): void
    {
        foreach ($this->categories as $key => $category) {
            if ($category->isEqual($event->getCategoryId())) {
                unset($this->categories[$key]);
            }
        }
    }

    protected function applyProductValueAddedEvent(ProductValueAddedEvent $event): void
    {
        $this->attributes[$event->getAttributeCode()->getValue()] = $event->getValue();
    }

    protected function applyProductValueChangedEvent(ProductValueChangedEvent $event): void
    {
        $this->attributes[$event->getAttributeCode()->getValue()] = $event->getTo();
    }

    protected function applyProductValueRemovedEvent(ProductValueRemovedEvent $event): void
    {
        unset($this->attributes[$event->getAttributeCode()->getValue()]);
    }

    protected function applyProductTemplateChangedEvent(ProductTemplateChangedEvent $event): void
    {
        $this->templateId = $event->getTemplateId();
    }
}
