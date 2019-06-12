<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\ValueObject;

/**
 */
class ProductStatus
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_TO_ACCEPT = 'to_accept';
    public const STATUS_TO_CORRECT = 'to_correct';

    public const AVAILABLE = [
        self::STATUS_DRAFT,
        self::STATUS_ACCEPTED,
        self::STATUS_TO_ACCEPT,
        self::STATUS_TO_CORRECT,
    ];

    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = strtolower(trim($value));
        if (!self::isValid($this->value)) {
            throw new \InvalidArgumentException(\sprintf('Value "%s" is not valid product status', $value));
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
