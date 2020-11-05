<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Transformer\Infrastructure\Converter\Mapper;

use Ergonode\Transformer\Infrastructure\Converter\ConverterInterface;
use Ergonode\Transformer\Infrastructure\Converter\MappingConverter;

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
            throw new \LogicException('Object of wrong class');
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
