<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Domain\ValueObject;

class Hash
{
    private const MIN_LENGTH = 32;
    private const MAX_LENGTH = 64;
    private const PATTERN = '/^[a-zA-Z0-9-_]+$\b/i';

    private string $value;

    public function __construct(string $value)
    {
        if (!self::isValid($value)) {
            throw new \InvalidArgumentException(\sprintf('Hash "%s" is incorrect', $value));
        }

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->getvalue();
    }

    public static function isValid(string $value): bool
    {
        return preg_match(self::PATTERN, $value, $matches) !== 0
            && mb_strlen($value) >= self::MIN_LENGTH
            && mb_strlen($value) <= self::MAX_LENGTH;
    }

    /**
     * @param Hash $hash
     */
    public function isEqual(self $hash): bool
    {
        return $hash->getValue() === $this->value;
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }
}
