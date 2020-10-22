<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model;

use JMS\Serializer\Annotation as JMS;

class Shopware6MediaDefaultFolder
{
    /**
     * @var string|null
     *
     * @JMS\Exclude()
     */
    private ?string $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("entity")
     */
    private ?string $entity;

    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("mediaFolderId")
     */
    private ?string $mediaFolderId;

    /**
     * @param string|null $id
     * @param string|null $entity
     * @param string|null $mediaFolderId
     */
    public function __construct(?string $id = null, ?string $entity = null, ?string $mediaFolderId = null)
    {
        $this->id = $id;
        $this->entity = $entity;
        $this->mediaFolderId = $mediaFolderId;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEntity(): string
    {
        return $this->entity;
    }

    /**
     * @param string $entity
     */
    public function setEntity(string $entity): void
    {
        $this->entity = $entity;
    }

    /**
     * @return string
     */
    public function getMediaFolderId(): string
    {
        return $this->mediaFolderId;
    }

    /**
     * @param string $mediaFolderId
     */
    public function setMediaFolderId(string $mediaFolderId): void
    {
        $this->mediaFolderId = $mediaFolderId;
    }
}
