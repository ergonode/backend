<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model\Product;

class Shopware6ProductCategory implements \JsonSerializable
{
    private ?string $id;

    public function __construct(?string $id)
    {
        $this->id = $id;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function jsonSerialize(): array
    {
        return ['id' => $this->id];
    }
}
