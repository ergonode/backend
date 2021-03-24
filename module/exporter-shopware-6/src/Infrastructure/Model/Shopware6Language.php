<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model;

class Shopware6Language
{
    private ?string $id;

    private ?string $name;

    private ?string  $localeId;

    private ?string $translationCodeId;

    private ?string $iso;

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
        $this->name = $name;
    }

    public function getLocaleId(): ?string
    {
        return $this->localeId;
    }

    public function setLocaleId(?string $localeId): void
    {
        $this->localeId = $localeId;
    }

    public function getTranslationCodeId(): ?string
    {
        return $this->translationCodeId;
    }

    public function setTranslationCodeId(?string $translationCodeId): void
    {
        $this->translationCodeId = $translationCodeId;
    }

    public function getIso(): ?string
    {
        return $this->iso;
    }

    public function setIso(?string $iso): void
    {
        $this->iso = $iso;
    }
}
