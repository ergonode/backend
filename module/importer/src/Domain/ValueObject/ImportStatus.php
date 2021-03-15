<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\ValueObject;

class ImportStatus
{
    public const CREATED = 'CREATED';
    public const PRECESSED = 'PRECESSED';
    public const ENDED = 'ENDED';
    public const STOPPED = 'STOPPED';
    public const ERROR = 'ERROR';

    public const AVAILABLE = [
        self::CREATED,
        self::PRECESSED,
        self::ENDED,
        self::STOPPED,
        self::ERROR,
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

    public function isCreated(): bool
    {
        return self::CREATED === $this->value;
    }

    public function isProcessed(): bool
    {
        return self::PRECESSED === $this->value;
    }

    public function isEnded(): bool
    {
        return self::ENDED === $this->value;
    }

    public function isStopped(): bool
    {
        return self::STOPPED === $this->value;
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
