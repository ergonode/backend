<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Model;

/**
 */
class AvailableColumn
{

    /**
     * @var string
     */
    private $type;

    /**
     * @var bool
     */
    private $multilingual;

    /**
     * @param string $type
     * @param bool   $multilingual
     */
    public function __construct(string $type, bool $multilingual)
    {
        $this->type = $type;
        $this->multilingual = $multilingual;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isMultilingual(): bool
    {
        return $this->multilingual;
    }
}
