<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Domain\Event;

use Ergonode\EventSourcing\Domain\Event\AbstractStringBasedChangedEvent;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ChannelNameChangedEvent extends AbstractStringBasedChangedEvent
{
    /**
     * @var ChannelId
     *
     * @JMS\Type("Ergonode\Workflow\Domain\Entity\ChannelId")
     */
    private ChannelId $id;

    /**
     * @param ChannelId $id
     * @param string    $from
     * @param string    $to
     */
    public function __construct(ChannelId $id, string $from, string $to)
    {
        parent::__construct($from, $to);

        $this->id = $id;
    }

    /**
     * @return ChannelId
     */
    public function getAggregateId(): ChannelId
    {
        return $this->id;
    }
}
