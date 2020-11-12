<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Converter\Mapper;

use Ergonode\Importer\Infrastructure\Converter\ConverterInterface;
use Ergonode\Importer\Infrastructure\Converter\DateConverter;
use Ergonode\Importer\Infrastructure\Exception\ConverterException;

class DateConverterMapper implements ConverterMapperInterface
{
    public function supported(ConverterInterface $converter): bool
    {
        return $converter instanceof DateConverter;
    }

    /**
     * @param ConverterInterface|DateConverter $converter
     * @param array                            $line
     *
     *
     * @throws \Exception
     */
    public function map(ConverterInterface $converter, array $line, ?string $default = null): ?string
    {
        if (!$converter instanceof DateConverter) {
            throw new \LogicException(
                sprintf(
                    'Expected an instance of %s. %s received.',
                    DateConverter::class,
                    get_debug_type($converter)
                )
            );
        }
        $field = $converter->getField();

        $result = strtotime($line[$field]);
        if (false === $result) {
            throw new ConverterException(sprintf('"%s" is unknown format date', $result));
        }

        $result = new \DateTime('@'.$result);

        return $result->format($converter->getFormat());
    }
}
