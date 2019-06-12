<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid;

/**
 */
interface FilterInterface
{
    /**
     * @return array
     */
    public function render(): array;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return bool
     */
    public function isEqual(): bool;

    /**
     * @return string|array[]
     */
    public function getValue();

    /**
     * @param string|array $value
     */
    public function setValue($value): void;
}
