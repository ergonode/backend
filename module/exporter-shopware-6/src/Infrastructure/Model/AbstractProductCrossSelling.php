<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model;

use Ergonode\ExporterShopware6\Infrastructure\Model\ProductCrossSelling\AbstractAssignedProduct;
use JMS\Serializer\Annotation as JMS;

abstract class AbstractProductCrossSelling
{
    /**
     * @JMS\Exclude()
     */
    private ?string $id;

    /**
     * @JMS\Type("string")
     * @JMS\SerializedName("name")
     */
    protected ?string $name;

    /**
     * @JMS\Type("string")
     * @JMS\SerializedName("productId")
     */
    protected ?string $productId;

    /**
     * @JMS\Type("bool")
     * @JMS\SerializedName("active")
     */
    protected bool $active;

    /**
     * @JMS\Type("string")
     * @JMS\SerializedName("type")
     */
    protected ?string $type;

    /**
     * @var AbstractAssignedProduct[]|null
     *
     * @JMS\Type("array<Ergonode\ExporterShopware6\Infrastructure\Model\ProductCrossSelling\AbstractAssignedProduct>")
     * @JMS\SerializedName("assignedProducts")
     */
    protected ?array $assignedProducts;

    /**
     * @JMS\Exclude()
     */
    protected bool $modified = false;

    /**
     * @param AbstractAssignedProduct[]|null $assignedProducts
     */
    public function __construct(
        ?string $id = null,
        ?string $name = null,
        ?string $productId = null,
        bool $active = true,
        string $type = 'productList',
        ?array $assignedProducts = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->productId = $productId;
        $this->active = $active;
        $this->type = $type;
        $this->assignedProducts = $assignedProducts;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
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

    public function getProductId(): ?string
    {
        return $this->productId;
    }

    public function setProductId(?string $productId): void
    {
        if ($this->productId !== $productId) {
            $this->productId = $productId;
            $this->modified = true;
        }
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        if ($this->active !== $active) {
            $this->active = $active;
            $this->modified = true;
        }
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        if ($this->type !== $type) {
            $this->type = $type;
            $this->modified = true;
        }
    }

    /**
     * @return AbstractAssignedProduct[]
     */
    public function getAssignedProducts(): array
    {
        if ($this->assignedProducts) {
            return $this->assignedProducts;
        }

        return [];
    }

    /**
     * @param AbstractAssignedProduct[]|null $assignedProducts
     */
    public function setAssignedProducts(?array $assignedProducts): void
    {
        $this->assignedProducts = $assignedProducts;
    }

    public function addAssignedProduct(AbstractAssignedProduct $assignedProduct): void
    {
        if (!$this->hasAssignedProduct($assignedProduct)) {
            $this->assignedProducts[] = $assignedProduct;
            $this->modified = true;
        }
    }

    public function hasAssignedProduct(AbstractAssignedProduct $assignedProduct): bool
    {
        foreach ($this->getAssignedProducts() as $assigned) {
            if ($assignedProduct->isEqual($assigned)) {
                return true;
            }
        }

        return false;
    }

    public function isModified(): bool
    {
        return $this->modified;
    }
}
