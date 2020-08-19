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
     * @var string|null
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("productNumber")
     */
    protected ?string $sku;

    /**
     * @var string|null
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("name")
     */
    protected ?string $name;

    /**
     * @var string|null
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("description")
     */
    protected ?string $description;

    /**
     * @var array|null
     *
     * @JMS\Type("array")
     * @JMS\SerializedName("categories")
     */
    protected ?array $categories;

    /**
     * @var array|null
     *
     * @JMS\Type("array")
     * @JMS\SerializedName("properties")
     */
    protected ?array $properties;

    /**
     * @var array|null
     *
     * @JMS\Type("array")
     * @JMS\SerializedName("customFields")
     */
    protected ?array $customFields;

    /**
     * @var bool
     *
     * @JMS\Type("bool")
     * @JMS\SerializedName("active")
     */
    protected bool $active;

    /**
     * @var int|null
     *
     * @JMS\Type("int")
     * @JMS\SerializedName("stock")
     */
    protected ?int $stock;

    /**
     * @var string|null
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("taxId")
     */
    protected ?string $taxId;

    /**
     * @var array|null
     *
     * @JMS\Type("array")
     * @JMS\SerializedName("price")
     */
    protected ?array $price;

    /**
     * @var string|null
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("parentId")
     */
    protected ?string $parentId;

    /**
     * @var array|null
     *
     * @JMS\Type("array")
     * @JMS\SerializedName("options")
     */
    protected ?array $options;

    /**
     * @var array|null
     *
     * @JMS\Type("array")
     * @JMS\SerializedName("media")
     */
    protected ?array $media;

    /**
     * @var Shopware6ProductConfiguratorSettings[]|null
     *
     * @JMS\Type("array<Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6ProductConfiguratorSettings>")
     * @JMS\SerializedName("configuratorSettings")
     */
    protected ?array $configuratorSettings = null;

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
     * @param array|null  $categories
     * @param array|null  $properties
     * @param array|null  $customFields
     * @param string|null $parentId
     * @param array|null  $options
     * @param bool        $active
     * @param int|null    $stock
     * @param string|null $taxId
     * @param array|null  $price
     * @param array|null  $media
     */
    public function __construct(
        ?string $id = null,
        ?string $sku = null,
        ?string $name = null,
        ?string $description = null,
        ?array $categories = null,
        ?array $properties = null,
        ?array $customFields = null,
        ?string $parentId = null,
        ?array $options = null,
        bool $active = true,
        ?int $stock = null,
        ?string $taxId = null,
        ?array $price = null,
        ?array $media = null
    ) {
        $this->id = $id;
        $this->sku = $sku;
        $this->name = $name;
        $this->description = $description;
        $this->categories = $categories;
        $this->properties = $properties;
        $this->customFields = $customFields;
        $this->parentId = $parentId;
        $this->options = $options;
        $this->active = $active;
        $this->stock = $stock;
        $this->taxId = $taxId;
        $this->price = $price;
        $this->media = $media;
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
     * @param string|null $name
     */
    public function setName(?string $name): void
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
     * @param string|null $description
     */
    public function setDescription(?string $description): void
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
        if ($this->categories) {
            return $this->categories;
        }

        return [];
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
        if ($this->customFields) {
            return $this->customFields;
        }

        return [];
    }

    /**
     * @param string       $customFieldId
     * @param string|array $value
     */
    public function addCustomField(string $customFieldId, $value): void
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
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        if ($active !== $this->active) {
            $this->active = $active;
            $this->modified = true;
        }
    }

    /**
     * @return int
     */
    public function getStock(): int
    {
        return $this->stock;
    }

    /**
     * @param int $stock
     */
    public function setStock(int $stock): void
    {
        if ($stock !== $this->stock) {
            $this->stock = $stock;
            $this->modified = true;
        }
    }

    /**
     * @return string
     */
    public function getTaxId(): string
    {
        return $this->taxId;
    }

    /**
     * @param string $taxId
     */
    public function setTaxId(string $taxId): void
    {
        if ($taxId !== $this->taxId) {
            $this->taxId = $taxId;
            $this->modified = true;
        }
    }

    /**
     * @return array
     */
    public function getPrice(): array
    {
        if ($this->price) {
            return $this->price;
        }

        return [];
    }

    /**
     * @param array $price
     */
    public function addPrice(array $price): void
    {
        $this->price[] = $price;
    }

    /**
     * @return string|null
     */
    public function getParentId(): ?string
    {
        return $this->parentId;
    }

    /**
     * @param string|null $parentId
     */
    public function setParentId(?string $parentId): void
    {
        if ($parentId !== $this->parentId) {
            $this->parentId = $parentId;
            $this->modified = true;
        }
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        if ($this->options) {
            return $this->options;
        }

        return [];
    }

    /**
     * @param string $option
     */
    public function addOptions(string $option): void
    {
        if (!$this->hasOption($option)) {
            $this->options[] = [
                'id' => $option,
            ];
            $this->modified = true;
        }
    }

    /**
     * @param string $optionId
     *
     * @return bool
     */
    public function hasOption(string $optionId): bool
    {
        foreach ($this->getOptions() as $option) {
            if ($option['id'] === $optionId) {
                return true;
            }
        }

        return false;
    }


    /**
     * @return array
     */
    public function getMedia(): array
    {
        if ($this->media) {
            return $this->media;
        }

        return [];
    }

    /**
     * @param string $mediaId
     */
    public function addMedia(string $mediaId): void
    {
        if (!$this->hasMedia($mediaId)) {
            $this->media[] = [
                'mediaId' => $mediaId,
            ];
            $this->modified = true;
        }
    }

    /**
     * @param string $mediaId
     *
     * @return bool
     */
    public function hasMedia(string $mediaId): bool
    {
        foreach ($this->getMedia() as $media) {
            if ($media['mediaId'] === $mediaId) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Shopware6ProductConfiguratorSettings[]|null $configuratorSettings
     */
    public function setConfiguratorSettings(?array $configuratorSettings): void
    {
        $this->configuratorSettings = $configuratorSettings;
    }

    /**
     * @return Shopware6ProductConfiguratorSettings[]
     */
    public function getConfiguratorSettings(): array
    {
        if ($this->configuratorSettings) {
            return $this->configuratorSettings;
        }

        return [];
    }

    /**
     * @param Shopware6ProductConfiguratorSettings $configuratorSetting
     */
    public function addConfiguratorSettings(Shopware6ProductConfiguratorSettings $configuratorSetting): void
    {
        if (!$this->hasConfiguratorSettings($configuratorSetting)) {
            $this->configuratorSettings[] = $configuratorSetting;
            $this->modified = true;
        }
    }

    /**
     * @param Shopware6ProductConfiguratorSettings $value
     *
     * @return bool
     */
    public function hasConfiguratorSettings(Shopware6ProductConfiguratorSettings $value): bool
    {
        foreach ($this->getConfiguratorSettings() as $configuratorSetting) {
            if ($configuratorSetting->getOptionId() === $value->getOptionId()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isNew(): bool
    {
        return null === $this->id;
    }

    /**
     * @return bool
     */
    public function isModified(): bool
    {
        return $this->modified;
    }
}
