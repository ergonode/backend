<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Domain\Entity;

use JMS\Serializer\Annotation as JMS;
use Ergonode\SharedKernel\Domain\AggregateId;
use Webmozart\Assert\Assert;

class Scheduler
{
    public const HOURS = 2147483647;
    public const MINUTES = 59;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\AggregateId")
     */
    private AggregateId $id;

    /**
     * @JMS\Type("boolean")
     */
    private bool $active;

    /**
     * @JMS\Type("DateTime")
     */
    private ?\DateTime $start;

    /**
     * @JMS\Type("integer")
     */
    private ?int $hour;

    /**
     * @JMS\Type("integer")
     */
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

    public function active(\DateTime $start, int $hour, int $minute): void
    {
        Assert::greaterThanEq($hour, 0);
        Assert::greaterThanEq($minute, 0);
        Assert::lessThanEq($hour, self::HOURS);
        Assert::lessThanEq($minute, self::MINUTES);

        $this->active = true;
        $this->start = $start;
        $this->hour = $hour;
        $this->minute = $minute;
    }

    public function deActive(): void
    {
        $this->active = false;
        $this->start = null;
        $this->hour = null;
        $this->minute = null;
    }
}
