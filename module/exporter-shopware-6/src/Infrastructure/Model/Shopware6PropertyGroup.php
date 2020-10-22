<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model;

use Ergonode\Core\Domain\ValueObject\Language;
use JMS\Serializer\Annotation as JMS;

class Shopware6PropertyGroup
{
    private const DISPLAY_TYPES = ['media', 'text', 'color'];
    private const SORTING_TYPES = ['numeric', 'alphanumeric', 'position'];

    /**
     * @var string|null
     *
     * @JMS\Exclude()
     */
    protected ?string $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("name")
     */
    protected ?string $name;

    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("displayType")
     */
    protected ?string $displayType;

    /**
     * @var string
     *
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
     * @var bool
     *
     * @JMS\Exclude()
     */
    protected bool $modified = false;

    /**
     * @param string|null $id
     * @param string|null $name
     * @param string|null $displayType
     * @param string|null $sortingType
     * @param array|null  $translations
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

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        if ($name !== $this->name) {
            $this->name = $name;
            $this->modified = true;
        }
    }

    /**
     * @return string
     */
    public function getDisplayType(): string
    {
        return $this->displayType;
    }

    /**
     * @param string $displayType
     */
    public function setDisplayType(string $displayType): void
    {
        if ($displayType !== $this->displayType && in_array($displayType, self::DISPLAY_TYPES)) {
            $this->displayType = $displayType;
            $this->modified = true;
        }
    }

    /**
     * @return string
     */
    public function getSortingType(): string
    {
        return $this->sortingType;
    }

    /**
     * @param string $sortingType
     */
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
