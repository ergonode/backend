<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model;

class Shopware6MediaDefaultFolder implements \JsonSerializable
{
    private ?string $id;

    private ?string $entity;

    private ?string $mediaFolderId;

    public function __construct(?string $id = null, ?string $entity = null, ?string $mediaFolderId = null)
    {
        $this->id = $id;
        $this->entity = $entity;
        $this->mediaFolderId = $mediaFolderId;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getEntity(): string
    {
        return $this->entity;
    }

    public function setEntity(string $entity): void
    {
        $this->entity = $entity;
    }

    public function getMediaFolderId(): string
    {
        return $this->mediaFolderId;
    }

    public function setMediaFolderId(string $mediaFolderId): void
    {
        $this->mediaFolderId = $mediaFolderId;
    }

    public function jsonSerialize(): array
    {
        return [
            'entity' => $this->entity,
            'mediaFolderId' => $this->mediaFolderId,
        ];
    }
}
