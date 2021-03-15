<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;

interface ColumnInterface
{
    public function getField(): string;

    public function getLabel(): ?string;

    public function getType(): string;

    public function isVisible(): bool;

    public function isEditable(): bool;

    public function isDeletable(): bool;

    public function getLanguage(): ?Language;

    public function hasLanguage(): bool;

    public function setDeletable(bool $deletable): void;

    public function setVisible(bool $visible): void;

    public function setLanguage(Language $language): void;

    public function getFilter(): ?FilterInterface;

    /**
     * @param string|array $value
     */
    public function setExtension(string $key, $value): void;

    public function setEditable(bool $editable): void;

    /**
     * @return array
     */
    public function getExtensions(): array;

    public function getAttribute(): ?AbstractAttribute;

    public function setAttribute(AbstractAttribute $attribute): void;

    public function getSuffix(): ?string;

    public function setSuffix(?string $suffix): void;

    public function getPrefix(): ?string;

    public function setPrefix(?string $prefix): void;

    public function supportView(string $view): bool;
}
