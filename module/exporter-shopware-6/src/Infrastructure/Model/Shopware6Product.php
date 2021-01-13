<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model;

use Ergonode\ExporterShopware6\Infrastructure\Model\Product\Shopware6ProductCategory;
use Ergonode\ExporterShopware6\Infrastructure\Model\Product\Shopware6ProductConfiguratorSettings;
use Ergonode\ExporterShopware6\Infrastructure\Model\Product\Shopware6ProductMedia;
use Ergonode\ExporterShopware6\Infrastructure\Model\Product\Shopware6ProductPrice;
use JMS\Serializer\Annotation as JMS;

class Shopware6Product
{
    /**
     * @JMS\Exclude()
     */
    private ?string $id;

    /**
     * @JMS\Type("string")
     * @JMS\SerializedName("productNumber")
     */
    private ?string $sku;

    /**
     * @JMS\Type("string")
     * @JMS\SerializedName("name")
     */
    private ?string $name;

    /**
     * @JMS\Type("string")
     * @JMS\SerializedName("description")
     */
    private ?string $description;

    /**
     * @var Shopware6ProductCategory[]|null
     *
     * @JMS\Type("array<Ergonode\ExporterShopware6\Infrastructure\Model\Product\Shopware6ProductCategory>")
     * @JMS\SerializedName("categories")
     */
    private ?array $categories = null;

    /**
     * @var array|null
     *
     * @JMS\Type("array")
     * @JMS\SerializedName("properties")
     */
    private ?array $properties;

    /**
     * @var array|null
     *
     * @JMS\Type("array")
     * @JMS\SerializedName("customFields")
     */
    private ?array $customFields;

    /**
     * @JMS\Type("bool")
     * @JMS\SerializedName("active")
     */
    private bool $active;

    /**
     * @JMS\Type("int")
     * @JMS\SerializedName("stock")
     */
    private ?int $stock;

    /**
     * @JMS\Type("string")
     * @JMS\SerializedName("taxId")
     */
    private ?string $taxId;

    /**
     * @var Shopware6ProductPrice[]|null
     *
     * @JMS\Type("array<Ergonode\ExporterShopware6\Infrastructure\Model\Product\Shopware6ProductPrice>")
     * @JMS\SerializedName("price")
     */
    private ?array $price;

    /**
     * @JMS\Type("string")
     * @JMS\SerializedName("parentId")
     */
    private ?string $parentId;

    /**
     * @var array|null
     *
     * @JMS\Type("array")
     * @JMS\SerializedName("options")
     */
    private ?array $options;

    /**
     * @var Shopware6ProductMedia[]|null
     *
     * @JMS\Type("array<Ergonode\ExporterShopware6\Infrastructure\Model\Product\Shopware6ProductMedia>")
     * @JMS\SerializedName("media")
     */
    private ?array $media = null;

    /**
     * @var Shopware6ProductConfiguratorSettings[]|null
     *
     * @JMS\Type("array<Ergonode\ExporterShopware6\Infrastructure\Model\Product\Shopware6ProductConfiguratorSettings>")
     * @JMS\SerializedName("configuratorSettings")
     */
    private ?array $configuratorSettings = null;

    /**
     * @JMS\Type("string")
     * @JMS\SerializedName("coverId")
     */
    private ?string $coverId;

    /**
     * @JMS\Type("string")
     * @JMS\SerializedName("metaTitle")
     */
    private ?string $metaTitle;

    /**
     * @JMS\Type("string")
     * @JMS\SerializedName("metaDescription")
     */
    private ?string $metaDescription;

    /**
     * @JMS\Type("string")
     * @JMS\SerializedName("keywords")
     */
    private ?string $keywords;

    /**
     * @var array
     *
     * @JMS\Exclude()
     */
    private array $propertyToRemove = [];

    /**
     * @var array
     *
     * @JMS\Exclude()
     */
    private array $categoryToRemove = [];

    /**
     * @var array
     *
     * @JMS\Exclude()
     */
    private array $mediaToRemove = [];

    /**
     * @JMS\Exclude()
     */
    private bool $modified = false;

    /**
     * @param array|null $properties
     * @param array|null $customFields
     * @param array|null $options
     * @param array|null $price
     */
    public function __construct(
        ?string $id = null,
        ?string $sku = null,
        ?string $name = null,
        ?string $description = null,
        ?array $properties = null,
        ?array $customFields = null,
        ?string $parentId = null,
        ?array $options = null,
        bool $active = true,
        ?int $stock = null,
        ?string $taxId = null,
        ?array $price = null,
        ?string $coverId = null,
        ?string $metaTitle = null,
        ?string $metaDescription = null,
        ?string $keywords = null
    ) {
        $this->id = $id;
        $this->sku = $sku;
        $this->name = $name;
        $this->description = $description;
        $this->properties = $properties;
        $this->customFields = $customFields;
        $this->parentId = $parentId;
        $this->options = $options;
        $this->active = $active;
        $this->stock = $stock;
        $this->taxId = $taxId;
        $this->price = $price;
        $this->coverId = $coverId;
        $this->metaTitle = $metaTitle;
        $this->metaDescription = $metaDescription;
        $this->keywords = $keywords;
        $this->setPropertyToRemove($properties);
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function setSku(string $sku): void
    {
        if ($sku !== $this->sku) {
            $this->sku = $sku;
            $this->modified = true;
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        if ($name !== $this->name) {
            $this->name = $name;
            $this->modified = true;
        }
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        if ($description !== $this->description) {
            $this->description = $description;
            $this->modified = true;
        }
    }

    /**
     * @param Shopware6ProductCategory[]|null $categories
     */
    public function setCategories(?array $categories): void
    {
        $this->categories = $categories;
        $this->setCategoryToRemove($categories);
    }

    /**
     * @return Shopware6ProductCategory[]
     */
    public function getCategories(): array
    {
        if ($this->categories) {
            return $this->categories;
        }

        return [];
    }

    public function addCategory(Shopware6ProductCategory $category): void
    {
        if (!$this->hasCategory($category)) {
            $this->categories[] = $category;
            $this->modified = true;
        }
        unset($this->categoryToRemove[$category->getId()]);
    }

    public function hasCategory(Shopware6ProductCategory $category): bool
    {
        foreach ($this->getCategories() as $productCategory) {
            if ($productCategory->getId() === $category->getId()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function getCategoryToRemove(): array
    {
        return $this->categoryToRemove;
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

    public function addProperty(string $propertyId): void
    {
        if (!$this->hasProperty($propertyId)) {
            $this->properties[] = [
                'id' => $propertyId,
            ];
            $this->modified = true;
        }
        unset($this->propertyToRemove[$propertyId]);
    }

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
     * @return array
     */
    public function getPropertyToRemove(): array
    {
        return $this->propertyToRemove;
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
     * @param string|array $value
     */
    public function addCustomField(string $customFieldId, $value): void
    {
        if ($this->hasCustomField($customFieldId)) {
            if ($this->customFields[$customFieldId] !== $value) {
                $this->customFields[$customFieldId] = $value;
                $this->modified = true;
            }
        } else {
            $this->customFields[$customFieldId] = $value;
            $this->modified = true;
        }
    }

    public function hasCustomField(string $customFieldId): bool
    {
        foreach (array_keys($this->getCustomFields()) as $key) {
            if ($key === $customFieldId) {
                return true;
            }
        }

        return false;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        if ($active !== $this->active) {
            $this->active = $active;
            $this->modified = true;
        }
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function setStock(int $stock): void
    {
        if ($stock !== $this->stock) {
            $this->stock = $stock;
            $this->modified = true;
        }
    }

    public function getTaxId(): string
    {
        return $this->taxId;
    }

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

    public function addPrice(Shopware6ProductPrice $price): void
    {
        if (!$this->hasPrice($price)) {
            $this->price[] = $price;
            $this->modified = true;
        }
        $this->changePrice($price);
    }

    public function hasPrice(Shopware6ProductPrice $price): bool
    {
        foreach ($this->getPrice() as $item) {
            if ($item->getCurrencyId() === $price->getCurrencyId()) {
                return true;
            }
        }

        return false;
    }

    public function getParentId(): ?string
    {
        return $this->parentId;
    }

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

    public function addOptions(string $option): void
    {
        if (!$this->hasOption($option)) {
            $this->options[] = [
                'id' => $option,
            ];
            $this->modified = true;
        }
    }

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
     * @param Shopware6ProductMedia[]|null $media
     */
    public function setMedia(?array $media): void
    {
        $this->media = $media;
        $this->setMediaToRemove($media);
    }

    /**
     * @return Shopware6ProductMedia[]
     */
    public function getMedia(): array
    {
        if ($this->media) {
            return $this->media;
        }

        return [];
    }

    public function addMedia(Shopware6ProductMedia $media): void
    {
        if (!$this->hasMedia($media)) {
            $this->media[] = $media;
            $this->modified = true;
        }
        unset($this->mediaToRemove[$media->getMediaId()]);
    }

    public function hasMedia(Shopware6ProductMedia $media): bool
    {
        foreach ($this->getMedia() as $productMedia) {
            if ($media->getMediaId() === $productMedia->getMediaId()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Shopware6ProductMedia[]
     */
    public function getMediaToRemove(): array
    {
        return $this->mediaToRemove;
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

    public function addConfiguratorSettings(Shopware6ProductConfiguratorSettings $configuratorSetting): void
    {
        if (!$this->hasConfiguratorSettings($configuratorSetting)) {
            $this->configuratorSettings[] = $configuratorSetting;
            $this->modified = true;
        }
    }

    public function hasConfiguratorSettings(Shopware6ProductConfiguratorSettings $value): bool
    {
        foreach ($this->getConfiguratorSettings() as $configuratorSetting) {
            if ($configuratorSetting->getOptionId() === $value->getOptionId()) {
                return true;
            }
        }

        return false;
    }

    public function getCoverId(): ?string
    {
        return $this->coverId;
    }

    public function setCoverId(?string $coverId): void
    {
        if ($this->coverId !== $coverId) {
            $this->coverId = $coverId;
            $this->modified = true;
        }
    }

    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

    public function setMetaTitle(?string $metaTitle): void
    {
        if ($this->metaTitle !== $metaTitle) {
            $this->metaTitle = $metaTitle;
            $this->modified = true;
        }
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function setMetaDescription(?string $metaDescription): void
    {
        if ($this->metaDescription !== $metaDescription) {
            $this->metaDescription = $metaDescription;
            $this->modified = true;
        }
    }

    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    public function setKeywords(?string $keywords): void
    {
        if ($this->keywords !== $keywords) {
            $this->keywords = $keywords;
            $this->modified = true;
        }
    }


    public function isNew(): bool
    {
        return null === $this->id;
    }

    public function isModified(): bool
    {
        return $this->modified;
    }

    public function hasItemToRemoved(): bool
    {
        return count($this->propertyToRemove) > 0
            || count($this->categoryToRemove) > 0
            || count($this->mediaToRemove) > 0;
    }

    /**
     * @param array|null $property
     */
    private function setPropertyToRemove(?array $property): void
    {
        if ($property) {
            foreach ($property as $item) {
                $this->propertyToRemove[$item['id']] = $item['id'];
            }
        }
    }

    /**
     * @param Shopware6ProductCategory[]|null $category
     */
    private function setCategoryToRemove(?array $category): void
    {
        if ($category) {
            foreach ($category as $item) {
                $this->categoryToRemove[$item->getId()] = $item->getId();
            }
        }
    }

    /**
     * @param array|null $media
     */
    private function setMediaToRemove(?array $media): void
    {
        if ($media) {
            foreach ($media as $item) {
                $this->mediaToRemove[$item->getMediaId()] = $item;
            }
        }
    }

    private function changePrice(Shopware6ProductPrice $price): void
    {
        foreach ($this->getPrice() as $item) {
            if (!$item->isEqual($price) && $item->getCurrencyId() === $price->getCurrencyId()) {
                $item->setNet($price->getNet());
                $item->setGross($price->getGross());
                $this->modified = true;
            }
        }
    }
}
