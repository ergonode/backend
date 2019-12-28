<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Converter\Mapper;

use Ergonode\Transformer\Infrastructure\Converter\ConverterInterface;
use Ergonode\Transformer\Infrastructure\Converter\MappingConverter;

/**
 */
class MappingConverterMapper implements ConverterMapperInterface
{
    /**
     * @param ConverterInterface $converter
     *
     * @return bool
     */
    public function supported(ConverterInterface $converter): bool
    {
        return $converter instanceof MappingConverter;
    }

    /**
     * @param ConverterInterface|MappingConverter $converter
     * @param array                               $line
     * @param string|null                         $default
     *
     * @return string|null
     */
    public function map(ConverterInterface $converter, array $line, ?string $default = null): ?string
    {
        $field = $converter->getField();
        $map = $converter->getMap();
        $value = $line[$field];

        if (isset($map[$value])) {
            return $map[$value];
        }

        return $value;
    }
}
