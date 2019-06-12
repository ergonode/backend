<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Column;

/**
 */
class MultiSelectColumn extends AbstractColumn
{
    public const TYPE = 'MULTI_SELECT';

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
     * @return array
     */
    public function render(string $id, array $row): array
    {
        $value = $row[$id];

        if (is_array($value)) {
            return $value;
        }

        if ($this->isJson($value)) {
            $value  = json_decode($row[$id], true);
        }

        if (is_array($value)) {
            return $value;
        }

        return $value ? [$value] : [];
    }

    /**
     * @param string $string
     *
     * @return bool
     */
    private function isJson(?string $string = null): bool
    {
        if (null === $string) {
            return false;
        }

        return (json_last_error() === JSON_ERROR_NONE);
    }
}
