<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode\Infrastructure\Model;

/**
 */
final class OptionModel
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
     * @var string
     */
    private string $attribute;

    /**
     * @var array
     */
    private array $translations = [];

    /**
     * @param string $id
     * @param string $code
     * @param string $attribute
     */
    public function __construct(string $id, string $code, string $attribute)
    {
        $this->id = $id;
        $this->code = $code;
        $this->attribute = $attribute;
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
     * @return string
     */
    public function getAttribute(): string
    {
        return $this->attribute;
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
     * @param string $label
     */
    public function addTranslation(string $language, string $label): void
    {
        $this->translations[$language] = $label;
    }
}
