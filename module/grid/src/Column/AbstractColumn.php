<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Column;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\ColumnInterface;
use Ergonode\Grid\FilterInterface;

abstract class AbstractColumn implements ColumnInterface
{
    private string $field;

    private ?string $label;

    private ?FilterInterface $filter;

    private bool $visible = true;

    private bool $editable = false;

    private bool $deletable = false;

    private ?Language $language = null;

    /**
     * @var string[]
     */
    private array $extensions;

    private ?AbstractAttribute $attribute = null;

    private ?string $suffix = null;

    private ?string $prefix = null;

    public function __construct(string $field, ?string $label = null, ?FilterInterface $filter = null)
    {
        $this->field = $field;
        $this->label = $label;
        $this->filter = $filter;
        $this->extensions = [];
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function getFilter(): ?FilterInterface
    {
        return $this->filter;
    }

    public function setVisible(bool $visible): void
    {
        $this->visible = $visible;
    }

    public function isVisible(): bool
    {
        return $this->visible;
    }

    public function isEditable(): bool
    {
        return $this->editable;
    }

    public function setEditable(bool $editable): void
    {
        $this->editable = $editable;
    }

    public function isDeletable(): bool
    {
        return $this->deletable;
    }

    public function setDeletable(bool $deletable): void
    {
        $this->deletable = $deletable;
    }

    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    public function hasLanguage(): bool
    {
        return null !== $this->language;
    }

    public function setLanguage(Language $language): void
    {
        $this->language = $language;
    }

    /**
     * @param string|array $value
     */
    public function setExtension(string $key, $value): void
    {
        $this->extensions[$key] = $value;
    }

    /**
     * @return array
     */
    public function getExtensions(): array
    {
        return $this->extensions;
    }

    public function getAttribute(): ?AbstractAttribute
    {
        return $this->attribute;
    }

    public function setAttribute(AbstractAttribute $attribute): void
    {
        $this->attribute = $attribute;
    }

    public function getSuffix(): ?string
    {
        return $this->suffix;
    }

    public function setSuffix(?string $suffix): void
    {
        $this->suffix = $suffix;
    }

    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    public function setPrefix(?string $prefix): void
    {
        $this->prefix = $prefix;
    }
}
