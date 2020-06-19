<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 */
class Shopware6Product
{
    /**
     * @var string|null
     *
     * @Serializer\Exclude()
     */
    protected ?string $id;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("productNumber")
     */
    protected ?string $sku;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("name")
     */
    protected ?string $name;

    /**
     * @var array
     *
     * @Serializer\Type("array")
     * @Serializer\SerializedName("categories")
     */
    protected array $categories = [];

    /**
     * @var bool
     *
     * @Serializer\Exclude()
     */
    protected bool $modified = false;

    /**
     * @param string|null $id
     * @param string|null $sku
     * @param string|null $name
     * @param array       $categories
     */
    public function __construct(
        ?string $id = null,
        ?string $sku = null,
        ?string $name = null,
        array $categories = []
    ) {
        $this->id = $id;
        $this->sku = $sku;
        $this->name = $name;
        $this->categories = $categories;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
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
     * @return bool
     */
    public function isModified(): bool
    {
        return $this->modified;
    }
}
