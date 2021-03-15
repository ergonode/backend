<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Converter;

use JMS\Serializer\Annotation as JMS;

class ConstConverter implements ConverterInterface
{
    public const TYPE = 'const';

    /**
     * @JMS\Type("string")
     */
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
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

    public function getValue(): string
    {
        return $this->value;
    }
}
