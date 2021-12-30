<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\SharedKernel\Domain;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

abstract class AbstractId
{
    private string $value;

    final public function __construct(string $value)
    {
        if (!Uuid::isValid($value)) {
            throw new \InvalidArgumentException(sprintf(
                '"%s" should be valid uuid value, given value "%s"',
                static::class,
                $value
            ));
        }

        $this->value = $value;
    }

    /**
     * @return AbstractId
     */
    public static function createFromUuid(UuidInterface $uuid): object
    {
        return new static($uuid->toString());
    }

    /**
     * @return static
     *
     * @throws \Exception
     */
    public static function generate(): object
    {
        return new static(Uuid::uuid4()->toString());
    }

    public static function isValid(string $value): bool
    {
        return Uuid::isValid($value);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function isEqual(AbstractId $id): bool
    {
        return $id->getValue() === $this->getValue();
    }
}
