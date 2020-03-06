<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Domain\ValueObject;

/**
 * Segment status representation
 */
class SegmentStatus
{
    public const NEW = 'NEW';
    public const PROCESSED = 'PROCESSED';
    public const CALCULATED = 'CALCULATED';
    public const OUTDATED = 'OUTDATED';

    public const AVAILABLE = [
        self::NEW,
        self::PROCESSED,
        self::CALCULATED,
        self::OUTDATED,
    ];

    /**
     * @var string
     */
    private string $value;

    /**
     * @param string $value
     */
    public function __construct(string $value = self::NEW)
    {
        $value = strtoupper($value);

        if (!self::isValid($value)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Segment status must be one of [%s]',
                    implode(',', self::AVAILABLE)
                )
            );
        }

        $this->value = $value;
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    public static function isValid(string $value): bool
    {
        return in_array(strtoupper($value), self::AVAILABLE, true);
    }

    /**
     * @return bool
     */
    public function isNew(): bool
    {
        return self::NEW === $this->value;
    }

    /**
     * @return bool
     */
    public function isProcessed(): bool
    {
        return self::PROCESSED === $this->value;
    }

    /**
     * @return bool
     */
    public function isCalculated(): bool
    {
        return self::CALCULATED === $this->value;
    }

    /**
     * @return bool
     */
    public function isOutdated(): bool
    {
        return self::OUTDATED === $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * @param SegmentStatus $status
     *
     * @return bool
     */
    public function isEqual(self $status): bool
    {
        return (string) $status === $this->value;
    }
}
