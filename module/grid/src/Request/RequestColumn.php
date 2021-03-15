<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Request;

use Ergonode\Core\Domain\ValueObject\Language;

class RequestColumn
{
    private string $column;

    private ?Language $language;

    private bool $show;

    public function __construct(string $column, ?Language $language = null, bool $show = true)
    {
        $this->column = $column;
        $this->language = $language;
        $this->show = $show;
    }

    public function getColumn(): string
    {
        return $this->column;
    }

    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    public function isShow(): bool
    {
        return $this->show;
    }

    public function getKey(): string
    {
        if ($this->language) {
            return sprintf('%s:%s', $this->column, $this->language->getCode());
        }

        return $this->column;
    }
}
