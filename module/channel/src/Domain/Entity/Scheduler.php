<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Domain\Entity;

use Ergonode\SharedKernel\Domain\AggregateId;
use Webmozart\Assert\Assert;

class Scheduler
{
    public const HOURS = 2147483647;
    public const MINUTES = 59;

    private AggregateId $id;

    private bool $active;

    private ?\DateTime $start;

    private ?int $hour;

    private ?int $minute;

    public function __construct(AggregateId $id)
    {
        $this->id = $id;
        $this->active = false;
        $this->start = null;
        $this->hour = null;
        $this->minute = null;
    }

    public function getId(): AggregateId
    {
        return $this->id;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getStart(): ?\DateTime
    {
        return $this->start;
    }

    public function getHour(): ?int
    {
        return $this->hour;
    }

    public function getMinute(): ?int
    {
        return $this->minute;
    }

    public function setUp(
        bool $active,
        \DateTime $start,
        int $hour,
        int $minute
    ): void {
        Assert::greaterThanEq($hour, 0);
        Assert::greaterThanEq($minute, 0);
        Assert::lessThanEq($hour, self::HOURS);
        Assert::lessThanEq($minute, self::MINUTES);
        if (0 === $hour) {
            Assert::greaterThan($minute, 0);
        }

        $this->active = $active;
        $this->start = $start;
        $this->hour = $hour;
        $this->minute = $minute;
    }
}
