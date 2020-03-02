<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Value\Domain\ValueObject;

use JMS\Serializer\Annotation as JMS;

/**
 */
class StringCollectionValue implements ValueInterface
{
    public const TYPE = 'string_collection';

    /**
     * @var string[]
     *
     * @JMS\Type("array<string>")
     */
    private array $value;

    /**
     * @param string[] $value
     */
    public function __construct(array $value)
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

    /**
     * @return string[]
     */
    public function getValue(): array
    {
        return $this->value;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return implode(',', $this->value);
    }
}
