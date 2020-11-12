<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Converter;

use JMS\Serializer\Annotation as JMS;

class SlugConverter implements ConverterInterface
{
    public const TYPE = 'slug';

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

    public function getField(): ?string
    {
        return $this->field;
    }
}
