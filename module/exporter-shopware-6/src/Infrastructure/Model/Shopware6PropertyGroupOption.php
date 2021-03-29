<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model;

use Ergonode\Core\Domain\ValueObject\Language;

class Shopware6PropertyGroupOption implements \JsonSerializable
{
    protected ?string $id;

    protected ?string $name;

    protected ?string $mediaId;

    protected ?int $position;

    protected ?array $translations;

    protected bool $modified = false;

    /**
     * @param array|null $translations
     */
    public function __construct(
        ?string $id = null,
        ?string $name = null,
        ?string $mediaId = null,
        ?int $position = null,
        ?array $translations = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->mediaId = $mediaId;
        $this->position = $position;
        $this->translations = $translations;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        if ($name !== $this->name) {
            $this->name = $name;
            $this->modified = true;
        }
    }

    public function getMediaId(): ?string
    {
        return $this->mediaId;
    }

    public function setMediaId(?string $mediaId): void
    {
        if ($mediaId !== $this->mediaId) {
            $this->mediaId = $mediaId;
            $this->modified = true;
        }
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): void
    {
        if ($position !== $this->position) {
            $this->position = $position;
            $this->modified = true;
        }
    }

    public function addTranslations(Language $language, string $field, string $value): void
    {
        $code = str_replace('_', '-', $language->getCode());

        $this->translations[$code][$field] = $value;
    }

    public function isModified(): bool
    {
        return $this->modified;
    }

    public function jsonSerialize(): array
    {
        $data = [
            'name' => $this->name,
        ];
        if (null !== $this->mediaId) {
            $data['mediaId'] = $this->mediaId;
        }
        if (null !== $this->position) {
            $data['position'] = $this->position;
        }
        if (null !== $this->translations) {
            $data['translations'] = $this->translations;
        }

        return $data;
    }
}
