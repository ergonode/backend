<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Converter\Mapper;

use Ergonode\Transformer\Infrastructure\Converter\ConverterInterface;
use Ergonode\Transformer\Infrastructure\Converter\DateConverter;
use Ergonode\Transformer\Infrastructure\Exception\ConverterException;

/**
 */
class DateConverterMapper implements ConverterMapperInterface
{
    /**
     * @param ConverterInterface $converter
     *
     * @return bool
     */
    public function supported(ConverterInterface $converter): bool
    {
        return $converter instanceof DateConverter;
    }

    /**
     * @param ConverterInterface|DateConverter $converter
     * @param array                            $line
     * @param string|null                      $default
     *
     * @return string|null
     *
     * @throws \Exception
     */
    public function map(ConverterInterface $converter, array $line, ?string $default = null): ?string
    {
        $field = $converter->getField();

        $result = strtotime($line[$field]);
        if (false === $result) {
            throw new ConverterException(sprintf('"%s" is unknown format date', $result));
        }

        $result = new \DateTime('@'.$result);

        return $result->format($converter->getFormat());
    }
}
