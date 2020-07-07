<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Infrastructure\Envelope;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\AggregateId;

/**
 */
class DomainEventEnvelope
{
    /**
     * @var AggregateId
     */
    private AggregateId $aggregateId;

    /**
     * @var int
     */
    private int $sequence;

    /**
     * @var DomainEventInterface
     */
    private DomainEventInterface $event;

    /**
     * @var \DateTime
     */
    private \DateTime $recordedAt;

    /**
     * @param AggregateId          $aggregateId
     * @param int                  $sequence
     * @param DomainEventInterface $event
     * @param \DateTime            $recordedAt
     */
    public function __construct(
        AggregateId $aggregateId,
        int $sequence,
        DomainEventInterface $event,
        \DateTime $recordedAt
    ) {
        $this->aggregateId = $aggregateId;
        $this->sequence = $sequence;
        $this->event = $event;
        $this->recordedAt = $recordedAt;
    }

    /**
     * @return AggregateId
     */
    public function getAggregateId(): AggregateId
    {
        return $this->aggregateId;
    }

    /**
     * @return int
     */
    public function getSequence(): int
    {
        return $this->sequence;
    }

    /**
     * @return DomainEventInterface
     */
    public function getEvent(): DomainEventInterface
    {
        return $this->event;
    }

    /**
     * @return \DateTime
     */
    public function getRecordedAt(): \DateTime
    {
        return $this->recordedAt;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return get_class($this->event);
    }
}
