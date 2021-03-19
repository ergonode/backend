<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Converter;

class JoinConverter implements ConverterInterface
{
    public const TYPE = 'join';

    private string $pattern;

    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
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

    public function getPattern(): string
    {
        return $this->pattern;
    }
}
