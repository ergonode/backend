<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\EventSourcing\Infrastructure\Envelope;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\AggregateId;

class DomainEventEnvelope
{
    private AggregateId $aggregateId;

    private int $sequence;

    private DomainEventInterface $event;

    private \DateTime $recordedAt;

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

    public function getAggregateId(): AggregateId
    {
        return $this->aggregateId;
    }

    public function getSequence(): int
    {
        return $this->sequence;
    }

    public function getEvent(): DomainEventInterface
    {
        return $this->event;
    }

    public function getRecordedAt(): \DateTime
    {
        return $this->recordedAt;
    }

    public function getType(): string
    {
        return get_class($this->event);
    }
}
