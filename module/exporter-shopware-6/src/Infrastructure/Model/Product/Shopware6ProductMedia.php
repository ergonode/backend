<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model\Product;

use JMS\Serializer\Annotation as JMS;

class Shopware6ProductMedia
{
    /**
     * @JMS\Type("string")
     * @JMS\SerializedName("id")
     */
    private ?string $id;

    /**
     * @JMS\Type("string")
     * @JMS\SerializedName("mediaId")
     */
    private ?string $mediaId;

    /**
     * @JMS\Type("int")
     * @JMS\SerializedName("position")
     */
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
}
