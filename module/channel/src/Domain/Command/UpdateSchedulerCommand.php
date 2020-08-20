<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Domain\Command;

use Ergonode\SharedKernel\Domain\AggregateId;
use Webmozart\Assert\Assert;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;

/**
 */
class UpdateSchedulerCommand implements DomainCommandInterface
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
     * @var \DateTime
     *
     * @JMS\Type("DateTime")
     */
    private \DateTime $start;

    /**
     * @var int
     *
     * @JMS\Type("integer")
     */
    private int $hour;

    /**
     * @var int
     *
     * @JMS\Type("integer")
     */
    private int $minute;

    /**
     * @param AggregateId $id
     * @param bool        $active
     * @param \DateTime   $start
     * @param int         $hour
     * @param int         $minute
     */
    public function __construct(AggregateId $id, bool $active, \DateTime $start, int $hour, int $minute)
    {
        Assert::greaterThanEq($hour, 0);
        Assert::greaterThanEq($minute, 0);
        Assert::lessThanEq($hour, 23);
        Assert::lessThanEq($minute, 59);

        $this->id = $id;
        $this->active = $active;
        $this->start = $start;
        $this->hour = $hour;
        $this->minute = $minute;
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
     * @return \DateTime
     */
    public function getStart(): \DateTime
    {
        return $this->start;
    }

    /**
     * @return int
     */
    public function getHour(): int
    {
        return $this->hour;
    }

    /**
     * @return int
     */
    public function getMinute(): int
    {
        return $this->minute;
    }
}
