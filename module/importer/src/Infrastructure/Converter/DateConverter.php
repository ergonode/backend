<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Converter;

class DateConverter implements ConverterInterface
{
    private string $field;

    private string $format;


    public function __construct(string $field, string $format)
    {
        $this->field = $field;
        $this->format = $format;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getFormat(): string
    {
        return $this->format;
    }
}
