<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Converter;

use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class SlugConverter implements ConverterInterface
{
    public const TYPE = 'slug';

    /**
     * @var string|null
     *
     * @JMS\Type("string")
     */
    private $field;

    /**
     * @param null|string $field
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
     * @param array  $line
     * @param string $field
     *
     * @return ValueInterface
     */
    public function map(array $line, string $field): ValueInterface
    {
        $field = $this->field ?: $field;

        $text = preg_replace('~[^\pL\d]+~u', '_', $line[$field]);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^_\w]+~', '', $text);
        $text = trim($text, '_');
        $text = preg_replace('~_+~', '_', $text);
        $text = strtolower($text);

        return new StringValue($text);
    }
}
