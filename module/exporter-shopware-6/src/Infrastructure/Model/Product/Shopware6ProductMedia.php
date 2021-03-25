<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model\Product;

class Shopware6ProductMedia implements \JsonSerializable
{
    private ?string $id;

    private ?string $mediaId;

    private int $position;

    public function __construct(?string $id = null, ?string $mediaId = null, int $position = 1)
    {
        $this->id = $id;
        $this->mediaId = $mediaId;
        $this->position = $position;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getMediaId(): ?string
    {
        return $this->mediaId;
    }

    public function setMediaId(?string $mediaId): void
    {
        $this->mediaId = $mediaId;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    public function jsonSerialize(): array
    {
        if ($this->id) {
            return [
                'id' => $this->id,
                'mediaId' => $this->mediaId,
                'position' => $this->position,
            ];
        }

        return [
            'mediaId' => $this->mediaId,
            'position' => $this->position,
        ];
    }
}
