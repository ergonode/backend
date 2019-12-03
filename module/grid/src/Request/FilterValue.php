<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Request;

use Ergonode\Core\Domain\ValueObject\Language;

/**
 *
 */
class FilterValue
{
    /**
     * @var string
     */
    private $operator;

    /**
     * @var string|null
     */
    private $value;

    /**
     * @var string
     */
    private $column;

    /**
     * @var Language|null
     */
    private $language;

    /**
     * @param string        $column
     * @param string        $operator
     * @param string        $value
     * @param Language|null $language
     */
    public function __construct(string $column, string $operator, ?string $value = null, ?Language $language = null)
    {
        $this->column = $column;
        $this->operator = $operator;
        $this->value = $value;
        $this->language = $language;
    }

    /**
     * @return string
     */
    public function getColumn(): string
    {
        return $this->column;
    }

    /**
     * @return string
     */
    public function getOperator(): string
    {
        return $this->operator;
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @return Language|null
     */
    public function getLanguage(): ?Language
    {
        return $this->language;
    }
}
