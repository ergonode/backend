<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode\Infrastructure\Model;

/**
 */
final class AttributeModel
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
    private string $type;

    /**
     * @var string
     */
    private string $hint;

    /**
     * @var string
     */
    private string $placeholder;

    /**
     * @var array
     */
    private array $translations;

    /**
     * @param string $id
     * @param string $code
     * @param string $type
     * @param string $hint
     * @param string $placeholder
     */
    public function __construct(
        string $id,
        string $code,
        string $type,
        string $hint,
        string $placeholder
    ) {
        $this->id = $id;
        $this->code = $code;
        $this->type = $type;
        $this->hint = $hint;
        $this->placeholder = $placeholder;
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
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getHint(): string
    {
        return $this->hint;
    }

    /**
     * @return string
     */
    public function getPlaceholder(): string
    {
        return $this->placeholder;
    }

    /**
     * @param string $language
     * @param string $name
     */
    public function addTranslation(string $language, string $name): void
    {
        $this->translations[$language] = $name;
    }

    /**
     * @return array
     */
    public function getTranslations(): array
    {
        return $this->translations;
    }
}