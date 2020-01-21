<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Domain\Entity;

use Ergonode\Channel\Domain\Event\ChannelCreatedEvent;
use Ergonode\Channel\Domain\Event\ChannelNameChangedEvent;
use Ergonode\Channel\Domain\Event\ChannelSegmentChangedEvent;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Segment\Domain\Entity\SegmentId;

/**
 */
class Channel extends AbstractAggregateRoot
{
    /**
     * @var ChannelId
     */
    private ChannelId $id;

    /**
     * @var TranslatableString
     */
    private TranslatableString $name;

    /**
     * @var SegmentId
     */
    private SegmentId $segmentId;

    /**
     * @param ChannelId          $channelId
     * @param TranslatableString $name
     * @param SegmentId          $segmentId
     *
     * @throws \Exception
     */
    public function __construct(ChannelId $channelId, TranslatableString $name, SegmentId $segmentId)
    {
        $this->apply(new ChannelCreatedEvent($channelId, $name, $segmentId));
    }

    /**
     * @return AbstractId|ChannelId
     */
    public function getId(): AbstractId
    {
        return $this->id;
    }

    /**
     * @param TranslatableString $name
     *
     * @throws \Exception
     */
    public function changeName(TranslatableString $name): void
    {
        if (!$this->name->isEqual($name)) {
            $this->apply(new ChannelNameChangedEvent($this->id, $this->name, $name));
        }
    }

    /**
     * @param SegmentId $segmentId
     *
     * @throws \Exception
     */
    public function changeSegment(SegmentId $segmentId): void
    {
        if (!$this->segmentId->isEqual($segmentId)) {
            $this->apply(new ChannelSegmentChangedEvent($this->id, $this->segmentId, $segmentId));
        }
    }

    /**
     * @return TranslatableString
     */
    public function getName(): TranslatableString
    {
        return $this->name;
    }

    /**
     * @return SegmentId
     */
    public function getSegmentId(): SegmentId
    {
        return $this->segmentId;
    }

    /**
     * @param ChannelCreatedEvent $event
     */
    public function applyChannelCreatedEvent(ChannelCreatedEvent $event): void
    {
        $this->id = $event->getAggregateId();
        $this->name = $event->getName();
        $this->segmentId = $event->getSegmentId();
    }

    /**
     * @param ChannelNameChangedEvent $event
     */
    public function applyChannelNameChangedEvent(ChannelNameChangedEvent $event): void
    {
        $this->name = $event->getTo();
    }

    /**
     * @param ChannelSegmentChangedEvent $event
     */
    public function applyChannelSegmentChangedEvent(ChannelSegmentChangedEvent $event): void
    {
        $this->segmentId = $event->getTo();
    }
}
