<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Domain\Event;

use Ergonode\Channel\Domain\Entity\ChannelId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Segment\Domain\Entity\SegmentId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ChannelCreatedEvent implements DomainEventInterface
{
    /**
     * @var ChannelId
     *
     * @JMS\Type("Ergonode\Channel\Domain\Entity\ChannelId")
     */
    private $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $name;

    /**
     * @var SegmentId
     *
     * @JMS\Type("Ergonode\Segment\Domain\Entity\SegmentId")
     */
    private $segmentId;

    /**
     * @param ChannelId $channelId
     * @param string    $name
     * @param SegmentId $segmentId
     */
    public function __construct(
        ChannelId $channelId,
        string $name,
        SegmentId $segmentId
    ) {
        $this->id = $channelId;
        $this->name = $name;
        $this->segmentId = $segmentId;
    }

    /**
     * @return ChannelId
     */
    public function getId(): ChannelId
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
}
