<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Model;

class CategoryModel
{
    private string $id;
    private string $code;
    private array $translations = [];

    public function __construct(string $id, string $code)
    {
        $this->id = $id;
        $this->code = $code;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getTranslations(): array
    {
        return $this->translations;
    }

    public function addTranslation(string $language, string $name): void
    {
        $this->translations[$language] = $name;
    }
}
