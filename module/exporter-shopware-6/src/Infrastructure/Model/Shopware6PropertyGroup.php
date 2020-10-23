<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model;

use Ergonode\Core\Domain\ValueObject\Language;
use JMS\Serializer\Annotation as JMS;

class Shopware6PropertyGroup
{
    private const DISPLAY_TYPES = ['media', 'text', 'color'];
    private const SORTING_TYPES = ['numeric', 'alphanumeric', 'position'];

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
     * @JMS\SerializedName("displayType")
     */
    protected ?string $displayType;

    /**
     * @JMS\Type("string")
     * @JMS\SerializedName("sortingType")
     */
    protected ?string $sortingType;

    /**
     * @var array
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
        ?string $displayType = 'text',
        ?string $sortingType = 'alphanumeric',
        ?array $translations = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->displayType = $displayType;
        $this->sortingType = $sortingType;
        $this->translations = $translations;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        if ($name !== $this->name) {
            $this->name = $name;
            $this->modified = true;
        }
    }

    public function getDisplayType(): string
    {
        return $this->displayType;
    }

    public function setDisplayType(string $displayType): void
    {
        if ($displayType !== $this->displayType && in_array($displayType, self::DISPLAY_TYPES)) {
            $this->displayType = $displayType;
            $this->modified = true;
        }
    }

    public function getSortingType(): string
    {
        return $this->sortingType;
    }

    public function setSortingType(string $sortingType): void
    {
        if ($sortingType !== $this->sortingType && in_array($sortingType, self::SORTING_TYPES)) {
            $this->sortingType = $sortingType;
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
