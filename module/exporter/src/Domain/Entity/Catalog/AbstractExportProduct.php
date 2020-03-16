<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 *  See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Domain\Entity\Catalog;

use JMS\Serializer\Annotation as JMS;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

/**
 */
abstract class AbstractExportProduct
{
    /**
     * @var Uuid
     *
     * @JMS\Type("uuid")
     */
    protected Uuid $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    protected string $sku;

    /**
     * @var Uuid[]
     *
     * @JMS\Type("array<string, uuid>")
     */
    protected array $categories;

    /**
     * @var AbstractExportAttributeValue[]
     *
     * @JMS\Type("array<string, Ergonode\Exporter\Domain\Entity\Catalog\AttributeValue\DefaultExportAttributeValue>")
     */
    protected array $attributes;

    /**
     * @param Uuid                           $id
     * @param string                         $sku
     * @param Uuid[]                         $categories
     * @param AbstractExportAttributeValue[] $attributes
     */
    public function __construct(Uuid $id, string $sku, array $categories = [], array $attributes = [])
    {
        Assert::Uuid($id);
        Assert::string($sku);
        Assert::allIsInstanceOf($categories, Uuid::class);
        Assert::allIsInstanceOf($attributes, AbstractExportAttributeValue::class);

        $this->id = $id;
        $this->sku = $sku;
        $this->categories = $categories;
        $this->attributes = $attributes;
    }

    /**
     * @return Uuid
     */
    public function getId(): Uuid
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
     * @return Uuid[]
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
     * @param Uuid $id
     */
    public function addCategory(Uuid $id): void
    {
        if (!$this->hasCategory($id)) {
            $this->categories[] = $id;
        }
    }

    /**
     * @param Uuid $id
     *
     * @return bool
     */
    public function hasCategory(Uuid $id): bool
    {
        foreach ($this->categories as $category) {
            if ($category->equals($id)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param AbstractExportAttributeValue $attribute
     */
    public function addAttribute(AbstractExportAttributeValue $attribute): void
    {
        $this->attributes[$attribute->getKey()] = $attribute;
    }

    /**
     * @param Uuid $id
     */
    public function removeCategory(Uuid $id): void
    {
        foreach ($this->categories as $key => $category) {
            if ($category->equals($id)) {
                unset($this->categories[$key]);
            }
        }
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
