<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Domain\Event;

use Ergonode\Channel\Domain\Entity\ChannelId;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ChannelDeletedEvent extends AbstractDeleteEvent
{
    /**
     * @var ChannelId
     *
     * @JMS\Type("Ergonode\Channel\Domain\Entity\ChannelId")
     */
    private ChannelId $id;

    /**
     * @param ChannelId $id
     */
    public function __construct(ChannelId $id)
    {
        $this->id = $id;
    }

    /**
     * @return ChannelId
     */
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }
}
