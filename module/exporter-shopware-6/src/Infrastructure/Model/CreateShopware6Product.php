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
class CreateShopware6Product extends Shopware6Product
{
    /**
     * @var bool
     *
     * @JMS\Type("bool")
     * @JMS\SerializedName("active")
     */
    private bool $active = true;

    /**
     * @var int
     *
     * @JMS\Type("int")
     * @JMS\SerializedName("stock")
     */
    private int $stock = 0;

    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("taxId")
     */
    private ?string $taxId;

    /**
     * @var array
     *
     * @JMS\Type("array")
     * @JMS\SerializedName("price")
     */
    private ?array $price;

    /**
     * @param string|null $sku
     * @param string|null $name
     * @param string|null $description
     * @param bool        $active
     * @param int         $stock
     * @param string|null $taxId
     * @param array|null  $price
     */
    public function __construct(
        ?string $sku = null,
        ?string $name = null,
        ?string $description = null,
        bool $active = false,
        int $stock = 0,
        string $taxId = null,
        array $price = null
    ) {
        parent::__construct(null, $sku, $name, $description);
        $this->active = $active;
        $this->stock = $stock;
        $this->taxId = $taxId;
        $this->price = $price;
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
        return $this->price;
    }

    /**
     * @param array $price
     */
    public function addPrice(array $price): void
    {
        $this->price[] = $price;
    }
}
