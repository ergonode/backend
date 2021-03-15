<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Converter\Mapper;

use Ergonode\Importer\Infrastructure\Converter\ConverterInterface;
use Ergonode\Importer\Infrastructure\Converter\ConstConverter;

class ConstConverterMapper implements ConverterMapperInterface
{
    public function supported(ConverterInterface $converter): bool
    {
        return $converter instanceof ConstConverter;
    }

    /**
     * @param ConverterInterface|ConstConverter $converter
     * @param array                             $line
     */
    public function map(ConverterInterface $converter, array $line, ?string $default = null): ?string
    {
        if (!$converter instanceof ConstConverter) {
            throw new \LogicException(
                sprintf(
                    'Expected an instance of %s. %s received.',
                    ConstConverter::class,
                    get_debug_type($converter)
                )
            );
        }

        return $converter->getValue();
    }
}
