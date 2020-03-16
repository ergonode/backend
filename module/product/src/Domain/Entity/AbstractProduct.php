<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Entity;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
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
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;

/**
 */
abstract class AbstractProduct extends AbstractAggregateRoot
{
    /**
     * @var ProductId
     */
    private ProductId $id;

    /**
     * @var Sku
     */
    private Sku $sku;

    /**
     * @var int
     */
    private int $version;

    /**
     * @var ValueInterface[]
     *
     * @JMS\Type("array<string, Ergonode\Value\Domain\ValueObject\ValueInterface>")
     */
    private array $attributes;

    /**
     * @var string[]
     *
     * @JMS\Type("array<string>")
     */
    private array $categories;

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
        Assert::allIsInstanceOf($categories, CategoryId::class);

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
     * @param CategoryId $categoryId
     *
     * @return bool
     */
    public function belongToCategory(CategoryId $categoryId): bool
    {
        return isset($this->categories[$categoryId->getValue()]);
    }

    /**
     * @param CategoryId $categoryId
     *
     * @throws \Exception
     */
    public function addToCategory(CategoryId $categoryId): void
    {
        if (!$this->belongToCategory($categoryId)) {
            $this->apply(new ProductAddedToCategoryEvent($this->id, $categoryId));
        }
    }

    /**
     * @param CategoryId $categoryId
     *
     * @throws \Exception
     */
    public function removeFromCategory(CategoryId $categoryId): void
    {
        if ($this->belongToCategory($categoryId)) {
            $this->apply(new ProductRemovedFromCategoryEvent($this->id, $categoryId));
        }
    }

    /**
     * @return CategoryId[]
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
        $this->categories[$event->getCategoryId()->getValue()] = $event->getCategoryId();
    }

    /**
     * @param ProductRemovedFromCategoryEvent $event
     */
    protected function applyProductRemovedFromCategoryEvent(ProductRemovedFromCategoryEvent $event): void
    {
        unset($this->categories[$event->getCategoryId()->getValue()]);
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
