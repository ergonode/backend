<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Converter;

use Ergonode\Transformer\Infrastructure\Exception\ConverterException;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
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
    private $format;

    /**
     * @var string|null
     *
     * @JMS\Type("string")
     */
    private $field;

    /**
     * @param string      $format
     * @param null|string $field
     */
    public function __construct(string $format, ?string $field = null)
    {
        $this->format = $format;
        $this->field = $field;
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
     * @param array  $line
     * @param string $field
     *
     * @return ValueInterface
     *
     * @throws ConverterException
     * @throws \Exception
     */
    public function map(array $line, string $field): ValueInterface
    {
        $field = $this->field ?: $field;

        $result = strtotime($line[$field]);
        if ($result === false) {
            throw new ConverterException(sprintf('"%s" is unknown format date', $result));
        }

        $result = new \DateTime('@'.$result);

        return new StringValue($result->format($this->format));
    }
}
