<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Converter;

use JMS\Serializer\Annotation as JMS;

class SlugConverter implements ConverterInterface
{
    public const TYPE = 'slug';

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $field;

    /**
     * @param string $field
     */
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

    /**
     * @return string
     */
    public function getField(): ?string
    {
        return $this->field;
    }
}
