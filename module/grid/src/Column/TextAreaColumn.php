<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Column;

/**
 */
class TextAreaColumn extends AbstractColumn
{
    public const TYPE = 'TEXT_AREA';

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @param string $id
     * @param array  $row
     *
     * @return string|null
     */
    public function render(string $id, array $row): ?string
    {
        return $row[$id];
    }
}
