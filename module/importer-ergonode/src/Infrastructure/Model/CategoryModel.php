<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode\Infrastructure\Model;

/**
 */
final class CategoryModel
{
    /**
     * @var string
     */
    private string $id;

    /**
     * @var string
     */
    private string $code;

    /**
     * @var array
     */
    private array $translations = [];

    /**
     * @param string $id
     * @param string $code
     */
    public function __construct(string $id, string $code)
    {
        $this->id = $id;
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return array
     */
    public function getTranslations(): array
    {
        return $this->translations;
    }

    /**
     * @param string $language
     * @param string $name
     */
    public function addTranslation(string $language, string $name): void
    {
        $this->translations[$language] = $name;
    }
}