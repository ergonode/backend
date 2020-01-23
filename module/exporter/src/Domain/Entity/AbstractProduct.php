<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 *  See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Domain\Entity;

use JMS\Serializer\Annotation as JMS;

/**
 */
abstract class AbstractProduct
{
    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    protected string $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    protected string $sku;

    /**
     * @var CategoryCode[]
     *
     * @JMS\Type("array<Ergonode\Exporter\Domain\Entity\CategoryCode>")
     */
    protected array $categories;


    /**
     * @var Attribute[]
     *
     * @JMS\Type("array<Ergonode\Exporter\Domain\Entity\Attribute>")
     */
    protected array $attributes;

    /**
     * AbstractProduct constructor.
     * @param string               $id
     * @param string               $sku
     * @param array|CategoryCode[] $categories
     * @param array|Attribute      $attributes
     */
    public function __construct(string $id, string $sku, array $categories = [], array $attributes = [])
    {
        $this->id = $id;
        $this->sku = $sku;
        $this->categories = $categories;
        $this->attributes = $attributes;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * @return CategoryCode[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @return Attribute[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param CategoryCode $category
     */
    public function addCategory(CategoryCode $category): void
    {
        foreach ($this->categories as $categoryCode) {
            if ($categoryCode === $category) {
                return;
            }
        }
        $this->categories[] = $category;
    }

    /**
     * @param Attribute $attribute
     */
    public function addAttribute(Attribute $attribute): void
    {
        $this->attributes[] = $attribute;
    }

    /**
     * @param CategoryCode $category
     */
    public function removeCategory(CategoryCode $category): void
    {
        foreach ($this->categories as $key => $productCategory) {
            if ($productCategory === $category) {
                unset($this->categories[$key]);
            }
        }
    }

    /**
     * @param Attribute $attribute
     */
    public function removeAttribute(Attribute $attribute): void
    {
        foreach ($this->attributes as $key => $productAttribute) {
            if ($productAttribute->getKey() === $attribute->getKey()) {
                unset($this->attributes[$key]);
            }
        }
    }

    /**
     * @param Attribute $newAttribute
     */
    public function changeAttribute(Attribute $newAttribute): void
    {
        $this->removeAttribute($newAttribute);
        $this->addAttribute($newAttribute);
    }
}
