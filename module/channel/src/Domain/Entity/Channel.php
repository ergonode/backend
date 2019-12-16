<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Domain\Entity;

use Ergonode\Channel\Domain\Event\ChannelCreatedEvent;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Segment\Domain\Entity\SegmentId;

/**
 */
class Channel extends AbstractAggregateRoot
{
    /**
     * @var ChannelId
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var SegmentId
     */
    private $segmentId;

    /**
     * @param ChannelId $channelId
     * @param string    $name
     * @param SegmentId $segmentId
     *
     * @throws \Exception
     */
    public function __construct(ChannelId $channelId, string $name, SegmentId $segmentId)
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
     * @return string
     */
    public function getName(): string
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
        $this->id = $event->getId();
        $this->name = $event->getName();
        $this->segmentId = $event->getSegmentId();
    }
}
