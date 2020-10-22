<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model;

use Ergonode\Core\Domain\ValueObject\Language;
use JMS\Serializer\Annotation as JMS;

class Shopware6PropertyGroupOption
{
    /**
     * @var string|null
     *
     * @JMS\Exclude()
     */
    protected ?string $id;

    /**
     * @var string|null
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("name")
     */
    protected ?string $name;

    /**
     * @var string|null
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("mediaId")
     */
    protected ?string $mediaId;

    /**
     * @var int|null
     *
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
     * @var bool
     *
     * @JMS\Exclude()
     */
    protected bool $modified = false;

    /**
     * @param string|null $id
     * @param string|null $name
     * @param string|null $mediaId
     * @param int|null    $position
     * @param array|null  $translations
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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        if ($name !== $this->name) {
            $this->name = $name;
            $this->modified = true;
        }
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
        if ($mediaId !== $this->mediaId) {
            $this->mediaId = $mediaId;
            $this->modified = true;
        }
    }

    /**
     * @return int|null
     */
    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     * @param int|null $position
     */
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

    /**
     * @param Language $language
     * @param string   $field
     * @param string   $value
     */
    public function addTranslations(Language $language, string $field, string $value): void
    {
        $code = str_replace('_', '-', $language->getCode());

        $this->translations[$code][$field] = $value;
    }

    /**
     * @return bool
     */
    public function isModified(): bool
    {
        return $this->modified;
    }
}
