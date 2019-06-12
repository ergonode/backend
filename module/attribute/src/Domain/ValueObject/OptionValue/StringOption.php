<?php

/**
 * Copyright © Ergonaut Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\ValueObject\OptionValue;

use Ergonode\Attribute\Domain\ValueObject\OptionInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class StringOption extends AbstractOption implements OptionInterface
{
    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $value;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * @return bool
     */
    public function isMultilingual(): bool
    {
        return false;
    }

    /**
     * @param OptionInterface $value
     *
     * @return bool
     */
    public function equal(OptionInterface $value): bool
    {
        return $value instanceof self && $this->value === $value->getValue();
    }
}
