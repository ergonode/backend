<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Value\Domain\ValueObject;

use Webmozart\Assert\Assert;

class StringValue implements ValueInterface
{
    public const TYPE = 'string';

    private string $value;

    public function __construct(string $value)
    {
        if (!self::isValid($value)) {
            throw new \InvalidArgumentException('Given string can\'t be empty');
        }

        $this->value = $value;
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @return string[]
     */
    public function getValue(): array
    {
        return [null => $this->value];
    }

    public function merge(ValueInterface $value): self
    {
        Assert::isInstanceOf($value, self::class);

        return new self((string) $value);
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return $this->value;
    }

    public static function isValid(string $value): bool
    {
        return '' !== $value;
    }

    public function isEqual(ValueInterface $value): bool
    {
        return
            $value instanceof self
            && $value->value === $this->value;
    }
}
