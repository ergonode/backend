<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Domain\ValueObject;

/**
 */
class State
{
    public const STATE_ENABLED = 'enabled';
    public const STATE_DELETED = 'deleted';

    public const AVAILABLE = [
        self::STATE_ENABLED,
        self::STATE_DELETED,
    ];

    /**
     * @var string
     */
    private string $value;

    /**
     * @param string $value
     */
    public function __construct(string $value = self::STATE_ENABLED)
    {
        $this->value = strtolower(trim($value));
        if (!self::isValid($this->value)) {
            throw new \InvalidArgumentException(\sprintf('Value "%s" is not valid state', $value));
        }
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
        return $this->getValue();
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    public static function isValid(string $value): bool
    {
        return \in_array($value, self::AVAILABLE, true);
    }
}
