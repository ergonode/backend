<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Converter;

use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class TextConverter implements ConverterInterface
{
    public const TYPE = 'text';

    /**
     * @var null|string
     *
     * @JMS\Type("string")
     *
     */
    private $field;

    /**
     * @param string|null $field
     */
    public function __construct(?string $field = null)
    {
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
     * @param array            $line
     * @param string           $field
     * @param StringValue|null $default
     *
     * @return ValueInterface|null
     */
    public function map(array $line, string $field, ?StringValue $default = null): ?ValueInterface
    {
        $field = $this->field ?: $field;

        if ('' !== $line[$field]) {
            return new StringValue($line[$field]);
        }

        return $default;
    }
}
