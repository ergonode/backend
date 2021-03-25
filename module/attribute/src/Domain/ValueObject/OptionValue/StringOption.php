<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\ValueObject\OptionValue;

use Ergonode\Attribute\Domain\ValueObject\OptionInterface;

class StringOption implements OptionInterface
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * {@inheritDoc}
     */
    public function isMultilingual(): bool
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function equal(OptionInterface $value): bool
    {
        return $value instanceof self && $this->value === $value->getValue();
    }
}
