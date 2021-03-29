<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model\Product;

class Shopware6ProductConfiguratorSettings implements \JsonSerializable
{
    private ?string $id;

    private ?string $optionId;

    public function __construct(?string $id = null, ?string $optionId = null)
    {
        $this->id = $id;
        $this->optionId = $optionId;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getOptionId(): ?string
    {
        return $this->optionId;
    }

    public function setOptionId(?string $optionId): void
    {
        $this->optionId = $optionId;
    }

    public function jsonSerialize(): array
    {
        if ($this->id) {
            return [
                'id' => $this->id,
                'optionId' => $this->optionId,
            ];
        }

        return [
            'optionId' => $this->optionId,
        ];
    }
}
