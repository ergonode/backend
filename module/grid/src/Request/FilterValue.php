<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Request;

use Ergonode\Core\Domain\ValueObject\Language;

class FilterValue
{
    private string $operator;

    private ?string $value;

    private string $column;

    private ?Language $language;

    public function __construct(string $column, string $operator, ?string $value = null, ?Language $language = null)
    {
        $this->column = $column;
        $this->operator = $operator;
        $this->value = $value;
        $this->language = $language;
    }

    public function getColumn(): string
    {
        return $this->column;
    }

    public function getOperator(): string
    {
        return $this->operator;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function getLanguage(): ?Language
    {
        return $this->language;
    }
}
