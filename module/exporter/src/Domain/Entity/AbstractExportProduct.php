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
abstract class AbstractExportProduct
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
     * @var ExportCategoryCode[]
     *
     * @JMS\Type("array<string, Ergonode\Exporter\Domain\Entity\ExportCategoryCode>")
     */
    protected array $categories;


    /**
     * @var AbstractExportAttributeValue[]
     *
     * @JMS\Type("array<string, Ergonode\Exporter\Domain\Entity\AttributeValue\DefaultExportAttributeValue>")
     */
    protected array $attributes;

    /**
     * AbstractExportProduct constructor.
     * @param string                             $id
     * @param string                             $sku
     * @param array|ExportCategoryCode[]         $categories
     * @param array|AbstractExportAttributeValue $attributes
     */
    public function __construct(string $id, string $sku, array $categories = [], array $attributes = [])
    {
        Assert::string($id);
        Assert::string($sku);
        Assert::allIsInstanceOf($categories, ExportCategoryCode::class);
        Assert::allIsInstanceOf($attributes, AbstractExportAttributeValue::class);

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
     * @return ExportCategoryCode[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @return AbstractExportAttributeValue[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param ExportCategoryCode $category
     */
    public function addCategory(ExportCategoryCode $category): void
    {
        $this->categories[$category->getCode()] = $category;
    }

    /**
     * @param AbstractExportAttributeValue $attribute
     */
    public function addAttribute(AbstractExportAttributeValue $attribute): void
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
     * @param AbstractExportAttributeValue $newAttribute
     */
    public function changeAttribute(AbstractExportAttributeValue $newAttribute): void
    {
        $this->removeAttribute($newAttribute->getKey());
        $this->addAttribute($newAttribute);
    }
}
