<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Converter\Mapper;

use Ergonode\Importer\Infrastructure\Converter\ConverterInterface;
use Ergonode\Importer\Infrastructure\Converter\TextConverter;

class TextConverterMapper implements ConverterMapperInterface
{
    public function supported(ConverterInterface $converter): bool
    {
        return $converter instanceof TextConverter;
    }

    /**
     * @param ConverterInterface|TextConverter $converter
     * @param array                            $line
     */
    public function map(ConverterInterface $converter, array $line, ?string $default = null): ?string
    {
        if (!$converter instanceof TextConverter) {
            throw new \LogicException(
                sprintf(
                    'Expected an instance of %s. %s received.',
                    TextConverter::class,
                    get_debug_type($converter)
                )
            );
        }
        $field = $converter->getField();
        $value = $line[$field];

        if ($value && '' !== $value) {
            return $value;
        }

        return $default;
    }
}
