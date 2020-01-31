<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Converter\Mapper;

use Ergonode\Transformer\Infrastructure\Converter\ConverterInterface;
use Ergonode\Transformer\Infrastructure\Converter\JoinConverter;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Value\Domain\ValueObject\StringValue;

/**
 */
class JoinConverterMapper implements ConverterMapperInterface
{
    /**
     * @param ConverterInterface $converter
     *
     * @return bool
     */
    public function supported(ConverterInterface $converter): bool
    {
        return $converter instanceof JoinConverter;
    }

    /**
     * @param ConverterInterface|JoinConverter $converter
     * @param array                            $line
     * @param string|null                      $default
     *
     * @return ValueInterface|null
     */
    public function map(ConverterInterface $converter, array $line, ?string $default = null): ?ValueInterface
    {
        $fields = [];
        foreach ($line as $key => $value) {
            $fields[sprintf('<%s>', $key)] = $value;
        }

        $result = str_replace(array_keys($fields), $fields, $converter->getPattern());

        return new StringValue($result);
    }
}
