<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Request;

use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
class RequestColumn
{
    /**
     * @var string
     */
    private string $column;

    /**
     * @var Language|null
     */
    private ?Language $language;

    /**
     * @var bool
     */
    private bool $show;

    /**
     * @param string        $column
     * @param Language|null $language
     * @param bool          $show
     */
    public function __construct(string $column, ?Language $language = null, bool $show = true)
    {
        $this->column = $column;
        $this->language = $language;
        $this->show = $show;
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
     * @return bool
     */
    public function isShow(): bool
    {
        return $this->show;
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
