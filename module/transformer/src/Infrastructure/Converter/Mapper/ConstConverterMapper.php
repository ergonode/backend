<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Converter\Mapper;

use Ergonode\Transformer\Infrastructure\Converter\ConverterInterface;
use Ergonode\Transformer\Infrastructure\Converter\ConstConverter;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Value\Domain\ValueObject\StringValue;

/**
 */
class ConstConverterMapper implements ConverterMapperInterface
{
    /**
     * @param ConverterInterface $converter
     *
     * @return bool
     */
    public function supported(ConverterInterface $converter): bool
    {
        return $converter instanceof ConstConverter;
    }

    /**
     * @param ConverterInterface|ConstConverter $converter
     * @param array                             $line
     * @param string|null                       $default
     *
     * @return ValueInterface|null
     */
    public function map(ConverterInterface $converter, array $line, ?string $default = null): ?ValueInterface
    {
        return new StringValue($converter->getValue());
    }
}
