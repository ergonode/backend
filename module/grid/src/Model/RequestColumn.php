<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Model;

use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
class RequestColumn
{
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
     * @param Language|null $language
     */
    public function __construct(string $column, ?Language $language = null)
    {
        $this->column = $column;
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
     * @return Language|null
     */
    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        if ($this->language) {
            return sprintf('%s:%s', $this->column, $this->language->getCode());
        }

        return $this->column;
    }
}
