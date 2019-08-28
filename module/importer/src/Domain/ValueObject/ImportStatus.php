<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Domain\ValueObject;

/**
 */
class ImportStatus
{
    public const CREATED = 'CREATED';
    public const PRECESSED = 'PRECESSED';
    public const ENDED = 'ENDED';
    public const STOPPED = 'STOPPED';

    public const AVAILABLE = [
        self::CREATED,
        self::PRECESSED,
        self::ENDED,
        self::STOPPED,
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

    /**
     * @return bool
     */
    public function isCreated(): bool
    {
        return self::CREATED === $this->value;
    }

    /**
     * @return bool
     */
    public function isProcessed(): bool
    {
        return self::PRECESSED === $this->value;
    }

    /**
     * @return bool
     */
    public function isEnded(): bool
    {
        return self::ENDED === $this->value;
    }

    /**
     * @return bool
     */
    public function isStopped(): bool
    {
        return self::STOPPED === $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
