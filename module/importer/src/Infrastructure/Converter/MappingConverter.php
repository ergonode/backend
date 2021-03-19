<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Converter;

use Webmozart\Assert\Assert;

class MappingConverter implements ConverterInterface
{
    public const TYPE = 'mapping';

    private string $field;

    /**
     * @var array
     *
     * @JMS\Type("array<string, string>")
     */
    private array $map;

    /**
     * @param array $map
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
