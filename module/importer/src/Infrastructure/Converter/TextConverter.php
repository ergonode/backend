<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Converter;

use JMS\Serializer\Annotation as JMS;

class TextConverter implements ConverterInterface
{
    public const TYPE = 'text';

    /**
     * @JMS\Type("string")
     */
    private string $field;

    public function __construct(string $field)
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

    public function getField(): string
    {
        return $this->field;
    }
}
