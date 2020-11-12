<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Converter\Mapper;

use Ergonode\Importer\Infrastructure\Converter\ConverterInterface;
use Ergonode\Importer\Infrastructure\Converter\SlugConverter;

class SlugConverterMapper implements ConverterMapperInterface
{
    public function supported(ConverterInterface $converter): bool
    {
        return $converter instanceof SlugConverter;
    }

    /**
     * @param ConverterInterface|SlugConverter $converter
     * @param array                            $line
     */
    public function map(ConverterInterface $converter, array $line, ?string $default = null): ?string
    {
        if (!$converter instanceof SlugConverter) {
            throw new \LogicException(
                sprintf(
                    'Expected an instance of %s. %s received.',
                    SlugConverter::class,
                    get_debug_type($converter)
                )
            );
        }
        $field = $converter->getField();

        if ($field && '' !== $field) {
            $text = preg_replace('~[^\pL\d]+~u', '_', $line[$field]);
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
            $text = preg_replace('~[^_\w]+~', '', $text);
            $text = trim($text, '_');
            $text = preg_replace('~_+~', '_', $text);
            $text = strtolower($text);

            return $text;
        }

        return $default;
    }
}
