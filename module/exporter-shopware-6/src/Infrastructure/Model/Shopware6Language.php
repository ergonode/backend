<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model;

/**
 */
class Shopware6Language
{
    /**
     * @var string|null
     */
    private ?string $id;

    /**
     * @var string|null
     */
    private ?string $name;

    /**
     * @var string|null
     */
    private ?string  $localeId;

    /**
     * @var string|null
     */
    private ?string $translationCodeId;

    /**
     * @var string|null
     */
    private ?string $iso;

    /**
     * @param string|null $id
     * @param string|null $name
     * @param string|null $localeId
     * @param string|null $translationCodeId
     * @param string|null $iso
     */
    public function __construct(
        ?string $id = null,
        ?string $name = null,
        ?string $localeId = null,
        ?string $translationCodeId = null,
        ?string $iso = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->localeId = $localeId;
        $this->translationCodeId = $translationCodeId;
        $this->iso = $iso;
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
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getLocaleId(): ?string
    {
        return $this->localeId;
    }

    /**
     * @param string|null $localeId
     */
    public function setLocaleId(?string $localeId): void
    {
        $this->localeId = $localeId;
    }

    /**
     * @return string|null
     */
    public function getTranslationCodeId(): ?string
    {
        return $this->translationCodeId;
    }

    /**
     * @param string|null $translationCodeId
     */
    public function setTranslationCodeId(?string $translationCodeId): void
    {
        $this->translationCodeId = $translationCodeId;
    }

    /**
     * @return string|null
     */
    public function getIso(): ?string
    {
        return $this->iso;
    }

    /**
     * @param string|null $iso
     */
    public function setIso(?string $iso): void
    {
        $this->iso = $iso;
    }
}
