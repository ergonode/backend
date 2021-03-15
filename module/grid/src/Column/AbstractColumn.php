<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Column;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\ColumnInterface;
use Ergonode\Grid\FilterInterface;

abstract class AbstractColumn implements ColumnInterface
{
    protected string $field;

    protected ?string $label;

    protected ?FilterInterface $filter;

    protected bool $visible = true;

    protected bool $editable = false;

    protected bool $deletable = false;

    protected ?Language $language = null;

    /**
     * @var string[]
     */
    protected array $extensions;

    protected ?AbstractAttribute $attribute = null;

    protected ?string $suffix = null;

    protected ?string $prefix = null;

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

    public function supportView(string $view): bool
    {
        return true;
    }
}
