<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Converter;

use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;

/**
 */
class MappingConverter implements ConverterInterface
{
    public const TYPE = 'mapping';

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $field;

    /**
     * @var array
     *
     * @JMS\Type("array<string, string>")
     */
    private $map;

    /**
     * @param string $field
     * @param array  $map
     */
    public function __construct(string $field, array $map)
    {
        Assert::allString($map);

        $this->map = $map;
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
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @return array
     */
    public function getMap(): array
    {
        return $this->map;
    }
}
