<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Converter\Mapper;

use Ergonode\Importer\Infrastructure\Converter\ConverterInterface;
use Ergonode\Importer\Infrastructure\Converter\MappingConverter;

class MappingConverterMapper implements ConverterMapperInterface
{
    public function supported(ConverterInterface $converter): bool
    {
        return $converter instanceof MappingConverter;
    }

    /**
     * @param ConverterInterface|MappingConverter $converter
     * @param array                               $line
     */
    public function map(ConverterInterface $converter, array $line, ?string $default = null): ?string
    {
        if (!$converter instanceof MappingConverter) {
            throw new \LogicException(
                sprintf(
                    'Expected an instance of %s. %s received.',
                    MappingConverter::class,
                    get_debug_type($converter)
                )
            );
        }
        $field = $converter->getField();
        $map = $converter->getMap();
        $value = $line[$field];

        if (array_key_exists($value, $map)) {
            return $map[$value];
        }

        return $default ?: null;
    }
}
