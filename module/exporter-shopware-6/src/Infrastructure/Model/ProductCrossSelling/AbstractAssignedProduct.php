<?php
/*
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model\ProductCrossSelling;

use JMS\Serializer\Annotation as JMS;

abstract class AbstractAssignedProduct
{
    /**
     * @JMS\Type("string")
     * @JMS\SerializedName("id")
     */
    private ?string $id;

    /**
     * @JMS\Type("string")
     * @JMS\SerializedName("productId")
     */
    protected ?string $productId;

    /**
     * @JMS\Type("int")
     * @JMS\SerializedName("position")
     */
    protected ?int $position;

    /**
     * @JMS\Exclude()
     */
    protected bool $modified = false;

    public function __construct(?string $id = null, ?string $productId = null, ?int $position = 1)
    {
        $this->id = $id;
        $this->productId = $productId;
        $this->position = $position;
    }

    public function getId(): ?string
    {
        return $this->id;
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

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): void
    {
        if ($this->position !== $position) {
            $this->position = $position;
            $this->modified = true;
        }
    }

    public function isEqual(AbstractAssignedProduct $assigned): bool
    {
        return $this->productId === $assigned->getProductId();
    }

    public function isModified(): bool
    {
        return $this->modified;
    }
}
