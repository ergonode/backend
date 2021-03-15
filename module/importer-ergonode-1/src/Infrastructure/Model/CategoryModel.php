<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Model;

class CategoryModel extends AbstractModel
{
    private string $code;
    private array $translations = [];

    public function __construct(string $code)
    {
        $this->code = $code;
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
