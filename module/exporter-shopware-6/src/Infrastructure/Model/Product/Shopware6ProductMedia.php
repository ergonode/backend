<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model\Product;

use JMS\Serializer\Annotation as JMS;

/**
 */
class Shopware6ProductMedia
{
    /**
     * @var string|null
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("id")
     */
    private ?string $id;

    /**
     * @var string|null
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("mediaId")
     */
    private ?string $mediaId;

    /**
     * @param string|null $id
     * @param string|null $mediaId
     */
    public function __construct(?string $id = null, ?string $mediaId = null)
    {
        $this->id = $id;
        $this->mediaId = $mediaId;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getMediaId(): ?string
    {
        return $this->mediaId;
    }

    /**
     * @param string|null $mediaId
     */
    public function setMediaId(?string $mediaId): void
    {
        $this->mediaId = $mediaId;
    }
}
