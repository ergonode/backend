<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Value\Domain\ValueObject;

use JMS\Serializer\Annotation as JMS;

/**
 */
class CollectionValue extends AbstractValue implements ValueInterface
{
    public const TYPE = 'string_collection';

    /**
     * @var string[]
     *
     * @JMS\Type("array<string>")
     */
    private $value;

    /**
     * @param string[] $value
     */
    public function __construct(array $value)
    {
        $this->value = $value;
    }

    /**
     * @return string
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
     * @return string
     */
    public function __toString(): string
    {
        return implode(',', $this->value);
    }
}
