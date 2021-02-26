<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Domain\Command;

use Ergonode\SharedKernel\Domain\AggregateId;
use Webmozart\Assert\Assert;
use JMS\Serializer\Annotation as JMS;
use Ergonode\Channel\Domain\Entity\Scheduler;

class UpdateSchedulerCommand implements ChannelCommandInterface
{
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
    private \DateTime $start;

    /**
     * @JMS\Type("integer")
     */
    private int $hour;

    /**
     * @JMS\Type("integer")
     */
    private int $minute;

    public function __construct(AggregateId $id, bool $active, \DateTime $start, int $hour, int $minute)
    {
        Assert::notNull($start);
        Assert::greaterThanEq($hour, 0);
        Assert::greaterThanEq($minute, 0);
        Assert::lessThanEq($hour, Scheduler::HOURS);
        Assert::lessThanEq($minute, Scheduler::MINUTES);
        if (0 === $hour) {
            Assert::greaterThan($minute, 0);
        }

        $this->id = $id;
        $this->active = $active;
        $this->start = $start;
        $this->hour = $hour;
        $this->minute = $minute;
    }

    public function getId(): AggregateId
    {
        return $this->id;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getStart(): \DateTime
    {
        return $this->start;
    }

    public function getHour(): int
    {
        return $this->hour;
    }

    public function getMinute(): int
    {
        return $this->minute;
    }
}
