<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Converter;

use JMS\Serializer\Annotation as JMS;

/**
 */
class DateConverter implements ConverterInterface
{
    public const TYPE = 'date';

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $field;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $format;


    /**
     * @param string $field
     * @param string $format
     */
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

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }
}
