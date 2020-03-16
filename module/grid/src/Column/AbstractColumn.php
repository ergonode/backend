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

/**
 */
abstract class AbstractColumn implements ColumnInterface
{
    /**
     * @var string
     */
    private string $field;

    /**
     * @var string
     */
    private ?string $label;

    /**
     * @var FilterInterface|null
     */
    private ?FilterInterface $filter;

    /**
     * @var bool
     */
    private bool $visible = true;

    /**
     * @var bool
     */
    private bool $editable = false;

    /**
     * @var bool
     */
    private bool $deletable = false;

    /**
     * @var Language|null
     */
    private ?Language $language = null;

    /**
     * @var string[]
     */
    private array $extensions;

    /**
     * @var AbstractAttribute|null
     */
    private ?AbstractAttribute $attribute = null;

    /**
     * @var string|null
     */
    private ?string $suffix = null;

    /**
     * @var string|null
     */
    private ?string $prefix = null;

    /**
     * @param string               $field
     * @param string|null          $label
     * @param FilterInterface|null $filter
     */
    public function __construct(string $field, ?string $label = null, ?FilterInterface $filter = null)
    {
        $this->field = $field;
        $this->label = $label;
        $this->filter = $filter;
        $this->extensions = [];
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @return FilterInterface|null
     */
    public function getFilter(): ?FilterInterface
    {
        return $this->filter;
    }

    /**
     * @param bool $visible
     */
    public function setVisible(bool $visible): void
    {
        $this->visible = $visible;
    }

    /**
     * @return bool
     */
    public function isVisible(): bool
    {
        return $this->visible;
    }

    /**
     * @return bool
     */
    public function isEditable(): bool
    {
        return $this->editable;
    }

    /**
     * @param bool $editable
     */
    public function setEditable(bool $editable): void
    {
        $this->editable = $editable;
    }

    /**
     * @return bool
     */
    public function isDeletable(): bool
    {
        return $this->deletable;
    }

    /**
     * @param bool $deletable
     */
    public function setDeletable(bool $deletable): void
    {
        $this->deletable = $deletable;
    }

    /**
     * @return Language|null
     */
    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    /**
     * @return bool
     */
    public function hasLanguage(): bool
    {
        return null !== $this->language;
    }

    /**
     * @param Language $language
     */
    public function setLanguage(Language $language): void
    {
        $this->language = $language;
    }

    /**
     * @param string       $key
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

    /**
     * @return AbstractAttribute|null
     */
    public function getAttribute(): ?AbstractAttribute
    {
        return $this->attribute;
    }

    /**
     * @param AbstractAttribute $attribute
     */
    public function setAttribute(AbstractAttribute $attribute): void
    {
        $this->attribute = $attribute;
    }

    /**
     * @return string|null
     */
    public function getSuffix(): ?string
    {
        return $this->suffix;
    }

    /**
     * @param string|null $suffix
     */
    public function setSuffix(?string $suffix): void
    {
        $this->suffix = $suffix;
    }

    /**
     * @return string|null
     */
    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    /**
     * @param string|null $prefix
     */
    public function setPrefix(?string $prefix): void
    {
        $this->prefix = $prefix;
    }
}
