<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Formatter;


/**
 */
class SlugFormatter
{
    public static function format(string $value): string
    {
        $value = preg_replace('~[^\pL\d]+~u', '_', $value);
        $value = iconv('utf-8', 'us-ascii//TRANSLIT', $value);
        $value = preg_replace('~[^_\w]+~', '', $value);
        $value = trim($value, '_');
        $value = preg_replace('~_+~', '_', $value);

        return strtolower($value);
    }
}
