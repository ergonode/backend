<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid;

use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
interface ColumnInterface
{
    /**
     * @return string
     */
    public function getField(): string;

    /**
     * @return string|null
     */
    public function getLabel(): ?string;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return bool
     */
    public function isVisible(): bool;

    /**
     * @return bool
     */
    public function isEditable(): bool;

    /**
     * @return Language
     */
    public function getLanguage(): ?Language;

    /**
     * @param Language $language
     */
    public function setLanguage(Language $language): void;

    /**
     * @return FilterInterface|null
     */
    public function getFilter(): ?FilterInterface;

    /**
     * @param string       $key
     * @param string|array $value
     */
    public function setExtension(string $key, $value): void;

    /**
     * @param bool $editable
     */
    public function setEditable(bool $editable): void;

    /**
     * @return array
     */
    public function getExtensions(): array;
}
