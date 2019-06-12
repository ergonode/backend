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
class JoinConverter extends AbstractConverter implements ConverterInterface
{
    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $pattern;

    /**
     * @param string $pattern
     */
    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * @param array  $line
     * @param string $field
     *
     * @return ValueInterface
     */
    public function map(array $line, string $field): ValueInterface
    {
        $fields = [];
        foreach ($line as $key => $value) {
            $fields[\sprintf('<%s>', $key)] = $value;
        }

        return new StringValue(str_replace(array_keys($fields), $fields, $this->pattern));
    }
}
