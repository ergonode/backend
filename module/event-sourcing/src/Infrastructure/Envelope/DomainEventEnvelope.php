<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Infrastructure\Envelope;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 */
class DomainEventEnvelope extends Event
{
    /**
     * @var AbstractId
     */
    private AbstractId $aggregateId;

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
     * @param AbstractId           $aggregateId
     * @param int                  $sequence
     * @param DomainEventInterface $event
     * @param \DateTime            $recordedAt
     */
    public function __construct(
        AbstractId $aggregateId,
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
     * @return AbstractId
     */
    public function getAggregateId(): AbstractId
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
     * @param \DateTime $recordedAt
     */
    public function setRecordedAt(\DateTime $recordedAt): void
    {
        $this->recordedAt = $recordedAt;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return get_class($this->event);
    }

    /**
     * @return DomainEventInterface
     */
    public function getPayload(): DomainEventInterface
    {
        return $this->event;
    }
}
