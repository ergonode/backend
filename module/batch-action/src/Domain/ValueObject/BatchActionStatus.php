<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Domain\ValueObject;

class BatchActionStatus
{
    public const PRECESSED = 'PRECESSED';
    public const WAITING_FOR_DECISION = 'WAITING_FOR_DECISION';
    public const ENDED = 'ENDED';

    public const AVAILABLE = [
        self::PRECESSED,
        self::WAITING_FOR_DECISION,
        self::ENDED,
    ];

    private string $value;

    public function __construct(string $value)
    {
        $value = \strtoupper($value);

        if (!\in_array($value, self::AVAILABLE, true)) {
            throw new \InvalidArgumentException(
                \sprintf(
                    'Required status must be one of [%s]',
                    \implode(',', self::AVAILABLE)
                )
            );
        }

        $this->value = $value;
    }

    public function isProcessed(): bool
    {
        return self::PRECESSED === $this->value;
    }

    public function isEnded(): bool
    {
        return self::ENDED === $this->value;
    }

    public function isWaitingForDecision(): bool
    {
        return self::WAITING_FOR_DECISION === $this->value;
    }

    public static function isValid(string $value): bool
    {
        return in_array(strtoupper($value), self::AVAILABLE);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
