<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Converter;

use JMS\Serializer\Annotation as JMS;

class DateConverter implements ConverterInterface
{
    public const TYPE = 'date';

    /**
     * @JMS\Type("string")
     */
    private string $field;

    /**
     * @JMS\Type("string")
     */
    private string $format;


    public function __construct(string $field, string $format)
    {
        $this->field = $field;
        $this->format = $format;
    }

    /**
     * {@inheritDoc}
     *
     * @JMS\VirtualProperty()
     */
    public function getType(): string
    {
        return self::TYPE;
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
