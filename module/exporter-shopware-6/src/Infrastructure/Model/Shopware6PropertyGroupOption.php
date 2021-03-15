<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model;

use Ergonode\Core\Domain\ValueObject\Language;
use JMS\Serializer\Annotation as JMS;

class Shopware6PropertyGroupOption
{
    /**
     * @JMS\Exclude()
     */
    protected ?string $id;

    /**
     * @JMS\Type("string")
     * @JMS\SerializedName("name")
     */
    protected ?string $name;

    /**
     * @JMS\Type("string")
     * @JMS\SerializedName("mediaId")
     */
    protected ?string $mediaId;

    /**
     * @JMS\Type("int")
     * @JMS\SerializedName("position")
     */
    protected ?int $position;

    /**
     * @var array|null
     *
     * @JMS\Type("array")
     * @JMS\SerializedName("translations")
     */
    protected ?array $translations;

    /**
     * @JMS\Exclude()
     */
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

    /**
     * @return array
     */
    public function getTranslations(): array
    {
        return $this->translations;
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
}
