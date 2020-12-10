<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Model;

class OptionModel
{
    private string $id;
    private string $code;
    private string $attribute;
    private array $translations = [];

    public function __construct(string $id, string $code, string $attribute)
    {
        $this->id = $id;
        $this->code = $code;
        $this->attribute = $attribute;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getAttribute(): string
    {
        return $this->attribute;
    }

    public function getTranslations(): array
    {
        return $this->translations;
    }

    public function addTranslation(string $language, string $label): void
    {
        $this->translations[$language] = $label;
    }
}
