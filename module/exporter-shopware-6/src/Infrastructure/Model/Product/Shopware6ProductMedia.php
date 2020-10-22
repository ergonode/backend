<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

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

    public function __construct(?string $id = null, ?string $mediaId = null)
    {
        $this->id = $id;
        $this->mediaId = $mediaId;
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
}
