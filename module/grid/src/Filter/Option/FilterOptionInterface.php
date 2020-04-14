<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Filter\Option;

/**
 */
interface FilterOptionInterface
{
    /**
     * @return string
     */
    public function getKey(): string;

    /**
     * @return array
     */
    public function render(): array;
}
