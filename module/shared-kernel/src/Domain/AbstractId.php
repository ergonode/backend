<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\SharedKernel\Domain;

use JMS\Serializer\Annotation as JMS;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

abstract class AbstractId
{
    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $value;

    /**
     * @param string $value
     */
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
     * @param UuidInterface $uuid
     *
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

    /**
     * @param string $value
     *
     * @return bool
     */
    public static function isValid(string $value): bool
    {
        return Uuid::isValid($value);
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
     * @param AbstractId $id
     *
     * @return bool
     */
    public function isEqual(AbstractId $id): bool
    {
        return $id->getValue() === $this->getValue();
    }
}
