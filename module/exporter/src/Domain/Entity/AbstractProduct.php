<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 *  See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Domain\Entity;

use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;

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
     * @JMS\Type("array<string, Ergonode\Exporter\Domain\Entity\CategoryCode>")
     */
    protected array $categories;


    /**
     * @var AbstractAttribute[]
     *
     * @JMS\Type("array<string, Ergonode\Exporter\Domain\Entity\Attribute\DefaultAttribute>")
     */
    protected array $attributes;

    /**
     * AbstractProduct constructor.
     * @param string                  $id
     * @param string                  $sku
     * @param array|CategoryCode[]    $categories
     * @param array|AbstractAttribute $attributes
     */
    public function __construct(string $id, string $sku, array $categories = [], array $attributes = [])
    {
        Assert::string($id);
        Assert::string($sku);
        Assert::allIsInstanceOf($categories, CategoryCode::class);
        Assert::allIsInstanceOf($attributes, AbstractAttribute::class);

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
     * @return AbstractAttribute[]
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
        $this->categories[$category->getCode()] = $category;
    }

    /**
     * @param AbstractAttribute $attribute
     */
    public function addAttribute(AbstractAttribute $attribute): void
    {
        $this->attributes[$attribute->getKey()] = $attribute;
    }

    /**
     * @param string $categoryCode
     */
    public function removeCategory(string $categoryCode): void
    {
        unset($this->categories[$categoryCode]);
    }

    /**
     * @param string $attributeKey
     */
    public function removeAttribute(string $attributeKey): void
    {
        unset($this->attributes[$attributeKey]);
    }

    /**
     * @param AbstractAttribute $newAttribute
     */
    public function changeAttribute(AbstractAttribute $newAttribute): void
    {
        $this->removeAttribute($newAttribute->getKey());
        $this->addAttribute($newAttribute);
    }
}
