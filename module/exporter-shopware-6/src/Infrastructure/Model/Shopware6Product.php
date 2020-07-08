<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model;

use JMS\Serializer\Annotation as JMS;

/**
 */
class Shopware6Product
{
    /**
     * @var string|null
     *
     * @JMS\Exclude()
     */
    protected ?string $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("productNumber")
     */
    protected ?string $sku;

    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("name")
     */
    protected ?string $name;

    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("description")
     */
    protected ?string $description;

    /**
     * @var array
     *
     * @JMS\Type("array")
     * @JMS\SerializedName("categories")
     */
    protected array $categories = [];

    /**
     * @var array|null
     *
     * @JMS\Type("array")
     * @JMS\SerializedName("properties")
     */
    protected ?array $properties = null;

    /**
     * @var array|null
     *
     * @JMS\Type("array")
     * @JMS\SerializedName("customFields")
     */
    protected ?array $customFields = null;

    /**
     * @var bool
     *
     * @JMS\Exclude()
     */
    protected bool $modified = false;

    /**
     * @param string|null $id
     * @param string|null $sku
     * @param string|null $name
     * @param string|null $description
     * @param array       $categories
     * @param array|null  $properties
     * @param array|null  $customFields
     */
    public function __construct(
        ?string $id = null,
        ?string $sku = null,
        ?string $name = null,
        ?string $description = null,
        array $categories = [],
        ?array $properties = null,
        ?array $customFields = null
    ) {
        $this->id = $id;
        $this->sku = $sku;
        $this->name = $name;
        $this->description = $description;
        $this->categories = $categories;
        $this->properties = $properties;
        $this->customFields = $customFields;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
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
     * @param string $sku
     */
    public function setSku(string $sku): void
    {
        if ($sku !== $this->sku) {
            $this->sku = $sku;
            $this->modified = true;
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        if ($name !== $this->name) {
            $this->name = $name;
            $this->modified = true;
        }
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        if ($description !== $this->description) {
            $this->description = $description;
            $this->modified = true;
        }
    }


    /**
     * @return array
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @param string $categoryId
     */
    public function addCategoryId(string $categoryId): void
    {
        if (!$this->hasCategory($categoryId)) {
            $this->categories[] = [
                'id' => $categoryId,
            ];
            $this->modified = true;
        }
    }

    /**
     * @param string $categoryId
     *
     * @return bool
     */
    public function hasCategory(string $categoryId): bool
    {
        foreach ($this->getCategories() as $category) {
            if ($category['id'] === $categoryId) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function getProperties(): array
    {
        if ($this->properties) {
            return $this->properties;
        }

        return [];
    }

    /**
     * @param string $propertyId
     */
    public function addProperty(string $propertyId): void
    {
        if (!$this->hasProperty($propertyId)) {
            $this->properties[] = [
                'id' => $propertyId,
            ];
            $this->modified = true;
        }
    }

    /**
     * @param string $propertyId
     *
     * @return bool
     */
    public function hasProperty(string $propertyId): bool
    {
        foreach ($this->getProperties() as $property) {
            if ($property['id'] === $propertyId) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array|null
     */
    public function getCustomFields(): ?array
    {
        return $this->customFields;
    }

    /**
     * @param string $customFieldId
     * @param string $value
     */
    public function addCustomField(string $customFieldId, string $value): void
    {
        if (!$this->hasCustomField($customFieldId)) {
            $this->customFields[$customFieldId] = $value;
            $this->modified = true;
        }
    }

    /**
     * @param string $customFieldId
     *
     * @return bool
     */
    public function hasCustomField(string $customFieldId): bool
    {
        foreach ($this->getCustomFields() as $key => $customField) {
            if ($key === $customFieldId) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isModified(): bool
    {
        return $this->modified;
    }
}
