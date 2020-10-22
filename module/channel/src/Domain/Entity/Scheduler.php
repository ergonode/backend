<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Domain\Entity;

use JMS\Serializer\Annotation as JMS;
use Ergonode\SharedKernel\Domain\AggregateId;
use Webmozart\Assert\Assert;

class Scheduler
{
    /**
     * @var AggregateId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\AggregateId")
     */
    private AggregateId $id;

    /**
     * @var bool
     *
     * @JMS\Type("boolean")
     */
    private bool $active;

    /**
     * @var \DateTime|null
     *
     * @JMS\Type("DateTime")
     */
    private ?\DateTime $start;

    /**
     * @var int|null
     *
     * @JMS\Type("integer")
     */
    private ?int $hour;

    /**
     * @var int|null
     *
     * @JMS\Type("integer")
     */
    private ?int $minute;

    /**
     * @param AggregateId $id
     */
    public function __construct(AggregateId $id)
    {
        $this->id = $id;
        $this->active = false;
        $this->start = null;
        $this->hour = null;
        $this->minute = null;
    }

    /**
     * @return AggregateId
     */
    public function getId(): AggregateId
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @return \DateTime|null
     */
    public function getStart(): ?\DateTime
    {
        return $this->start;
    }

    /**
     * @return int|null
     */
    public function getHour(): ?int
    {
        return $this->hour;
    }

    /**
     * @return int|null
     */
    public function getMinute(): ?int
    {
        return $this->minute;
    }

    /**
     * @param \DateTime $start
     * @param int       $hour
     * @param int       $minute
     */
    public function active(\DateTime $start, int $hour, int $minute): void
    {
        Assert::greaterThanEq($hour, 0);
        Assert::greaterThanEq($minute, 0);
        Assert::lessThanEq($hour, 23);
        Assert::lessThanEq($minute, 59);

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
