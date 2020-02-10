<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Entity;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Editor\Domain\Entity\ProductDraft;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Product\Domain\Event\ProductAddedToCategoryEvent;
use Ergonode\Product\Domain\Event\ProductCreatedEvent;
use Ergonode\Product\Domain\Event\ProductRemovedFromCategoryEvent;
use Ergonode\Product\Domain\Event\ProductValueAddedEvent;
use Ergonode\Product\Domain\Event\ProductValueChangedEvent;
use Ergonode\Product\Domain\Event\ProductValueRemovedEvent;
use Ergonode\Product\Domain\Event\ProductVersionIncreasedEvent;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;

/**
 */
abstract class AbstractProduct extends AbstractAggregateRoot
{
    /**
     * @var ProductId
     */
    private $id;

    /**
     * @var Sku
     */
    private $sku;

    /**
     * @var int
     */
    private $version;

    /**
     * @var ValueInterface[]
     *
     * @JMS\Type("array<string, Ergonode\Value\Domain\ValueObject\ValueInterface>")
     */
    private $attributes;

    /**
     * @var string[]
     *
     * @JMS\Type("array<string>")
     */
    private $categories;

    /**
     * @param ProductId $id
     * @param Sku       $sku
     * @param array     $categories
     * @param array     $attributes
     *
     * @throws \Exception
     */
    public function __construct(
        ProductId $id,
        Sku $sku,
        array $categories = [],
        array $attributes = []
    ) {
        Assert::allIsInstanceOf($categories, CategoryCode::class);

        $attributes = array_filter(
            $attributes,
            function ($value) {
                return $value !== null;
            }
        );

        $this->apply(new ProductCreatedEvent($id, $sku, $categories, $attributes));
    }

    /**
     * @return ProductId
     */
    public function getId(): ProductId
    {
        return $this->id;
    }

    /**
     * @return Sku
     */
    public function getSku(): Sku
    {
        return $this->sku;
    }

    /**
     * @param ProductDraft $draft
     *
     * @throws \Exception
     */
    public function applyDraft(ProductDraft $draft): void
    {
        $attributes = $draft->getAttributes();
        foreach ($attributes as $code => $value) {
            $attributeCode = new AttributeCode((string) $code);
            if ($this->hasAttribute($attributeCode)) {
                $this->changeAttribute($attributeCode, $value);
            } else {
                $this->addAttribute($attributeCode, $value);
            }
        }

        foreach ($this->getAttributes() as $code => $attributes) {
            $attributeCode = new AttributeCode((string) $code);
            if (!$draft->hasAttribute($attributeCode)) {
                $this->removeAttribute($attributeCode);
            }
        }
    }

    /**
     * @param CategoryCode $categoryCode
     *
     * @return bool
     */
    public function belongToCategory(CategoryCode $categoryCode): bool
    {
        return isset($this->categories[$categoryCode->getValue()]);
    }

    /**
     * @param CategoryCode $categoryCode
     *
     * @throws \Exception
     */
    public function addToCategory(CategoryCode $categoryCode): void
    {
        if (!$this->belongToCategory($categoryCode)) {
            $this->apply(new ProductAddedToCategoryEvent($this->id, $categoryCode));
        }
    }

    /**
     * @param CategoryCode $categoryCode
     *
     * @throws \Exception
     */
    public function removeFromCategory(CategoryCode $categoryCode): void
    {
        if ($this->belongToCategory($categoryCode)) {
            $this->apply(new ProductRemovedFromCategoryEvent($this->id, $categoryCode));
        }
    }

    /**
     * @return CategoryCode[]
     */
    public function getCategories(): array
    {
        return array_values($this->categories);
    }

    /**
     * @param AttributeCode $attributeCode
     *
     * @return bool
     */
    public function hasAttribute(AttributeCode $attributeCode): bool
    {
        return isset($this->attributes[$attributeCode->getValue()]);
    }

    /**
     * @param AttributeCode $attributeCode
     *
     * @return ValueInterface
     */
    public function getAttribute(AttributeCode $attributeCode): ValueInterface
    {
        if (!$this->hasAttribute($attributeCode)) {
            throw new \RuntimeException(sprintf('Value for attribute %s not exists', $attributeCode->getValue()));
        }

        return clone $this->attributes[$attributeCode->getValue()];
    }

    /**
     * @param AttributeCode  $attributeCode
     * @param ValueInterface $value
     *
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
     * @return ValueInterface[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param AttributeCode  $attributeCode
     * @param ValueInterface $value
     *
     * @throws \Exception
     */
    public function changeAttribute(AttributeCode $attributeCode, ValueInterface $value): void
    {
        if (!$this->hasAttribute($attributeCode)) {
            throw new \RuntimeException('Value note exists');
        }

        if ((string) $this->attributes[$attributeCode->getValue()] !== (string) $value) {
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
     * @param AttributeCode $attributeCode
     *
     * @throws \Exception
     */
    public function removeAttribute(AttributeCode $attributeCode): void
    {
        if (!$this->hasAttribute($attributeCode)) {
            throw new \RuntimeException('Value note exists');
        }

        $this->apply(
            new ProductValueRemovedEvent($this->id, $attributeCode, $this->attributes[$attributeCode->getValue()])
        );
    }

    /**
     * @param ProductCreatedEvent $event
     */
    protected function applyProductCreatedEvent(ProductCreatedEvent $event): void
    {
        $this->id = $event->getAggregateId();
        $this->sku = $event->getSku();
        $this->attributes = [];
        $this->categories = [];
        $this->version = 1;
        foreach ($event->getCategories() as $category) {
            $this->categories[$category->getValue()] = $category;
        }
        foreach ($event->getAttributes() as $key => $attribute) {
            $this->attributes[$key] = $attribute;
        }
    }

    /**
     * @param ProductAddedToCategoryEvent $event
     */
    protected function applyProductAddedToCategoryEvent(ProductAddedToCategoryEvent $event): void
    {
        $this->categories[$event->getCategoryCode()->getValue()] = $event->getCategoryCode();
    }

    /**
     * @param ProductRemovedFromCategoryEvent $event
     */
    protected function applyProductRemovedFromCategoryEvent(ProductRemovedFromCategoryEvent $event): void
    {
        unset($this->categories[$event->getCategoryCode()->getValue()]);
    }

    /**
     * @param ProductValueAddedEvent $event
     */
    protected function applyProductValueAddedEvent(ProductValueAddedEvent $event): void
    {
        $this->attributes[$event->getAttributeCode()->getValue()] = $event->getValue();
    }

    /**
     * @param ProductValueChangedEvent $event
     */
    protected function applyProductValueChangedEvent(ProductValueChangedEvent $event): void
    {
        $this->attributes[$event->getAttributeCode()->getValue()] = $event->getTo();
    }

    /**
     * @param ProductValueRemovedEvent $event
     */
    protected function applyProductValueRemovedEvent(ProductValueRemovedEvent $event): void
    {
        unset($this->attributes[$event->getAttributeCode()->getValue()]);
    }

    /**
     * @param ProductVersionIncreasedEvent $event
     */
    protected function applyProductVersionIncreasedEvent(ProductVersionIncreasedEvent $event): void
    {
        $this->version = $event->getTo();
    }
}
