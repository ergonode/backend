<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Converter\Mapper;

use Ergonode\Transformer\Infrastructure\Converter\ConverterInterface;
use Ergonode\Transformer\Infrastructure\Converter\MappingConverter;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Value\Domain\ValueObject\StringValue;

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
     * @return ValueInterface|null
     */
    public function map(ConverterInterface $converter, array $line, ?string $default = null): ?ValueInterface
    {
        $field = $converter->getField();
        $map = $converter->getMap();
        $value = $line[$field];

        if (array_key_exists($value, $map)) {
            return new StringValue($map[$value]);
        }

        return $default ? new StringValue($default) : null;
    }
}
