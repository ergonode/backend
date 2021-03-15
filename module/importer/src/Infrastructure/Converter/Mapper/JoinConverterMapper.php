<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Converter\Mapper;

use Ergonode\Importer\Infrastructure\Converter\ConverterInterface;
use Ergonode\Importer\Infrastructure\Converter\JoinConverter;

class JoinConverterMapper implements ConverterMapperInterface
{
    public function supported(ConverterInterface $converter): bool
    {
        return $converter instanceof JoinConverter;
    }

    /**
     * @param ConverterInterface|JoinConverter $converter
     * @param array                            $line
     */
    public function map(ConverterInterface $converter, array $line, ?string $default = null): ?string
    {
        $fields = [];
        foreach ($line as $key => $value) {
            $fields[sprintf('<%s>', $key)] = $value;
        }

        if (!$converter instanceof JoinConverter) {
            throw new \LogicException(
                sprintf(
                    'Expected an instance of %s. %s received.',
                    JoinConverter::class,
                    get_debug_type($converter)
                )
            );
        }

        return str_replace(array_keys($fields), $fields, $converter->getPattern());
    }
}
