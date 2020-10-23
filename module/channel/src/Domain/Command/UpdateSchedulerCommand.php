<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Domain\Command;

use Ergonode\SharedKernel\Domain\AggregateId;
use Webmozart\Assert\Assert;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use JMS\Serializer\Annotation as JMS;

class UpdateSchedulerCommand implements DomainCommandInterface
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
    private ?\DateTime $start;

    /**
     * @JMS\Type("integer")
     */
    private ?int $hour;

    /**
     * @JMS\Type("integer")
     */
    private ?int $minute;

    public function __construct(AggregateId $id, bool $active, ?\DateTime $start, ?int $hour, ?int $minute)
    {
        if ($active) {
            Assert::notNull($start);
            Assert::greaterThanEq($hour, 0);
            Assert::greaterThanEq($minute, 0);
            Assert::lessThanEq($hour, 23);
            Assert::lessThanEq($minute, 59);
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
