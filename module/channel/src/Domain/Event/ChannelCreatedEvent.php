<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ChannelCreatedEvent implements DomainEventInterface
{
    /**
     * @var ChannelId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ChannelId")
     */
    private ChannelId $id;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $name;

    /**
     * @var SegmentId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\SegmentId")
     */
    private SegmentId $segmentId;

    /**
     * @param ChannelId          $channelId
     * @param TranslatableString $name
     * @param SegmentId          $segmentId
     */
    public function __construct(
        ChannelId $channelId,
        TranslatableString $name,
        SegmentId $segmentId
    ) {
        $this->id = $channelId;
        $this->name = $name;
        $this->segmentId = $segmentId;
    }

    /**
     * @return ChannelId
     */
    public function getAggregateId(): ChannelId
    {
        return $this->id;
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
}
