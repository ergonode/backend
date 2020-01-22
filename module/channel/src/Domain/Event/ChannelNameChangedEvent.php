<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Domain\Event;

use Ergonode\Channel\Domain\Entity\ChannelId;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Domain\Event\AbstractTranslatableStringBasedChangedEvent;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ChannelNameChangedEvent extends AbstractTranslatableStringBasedChangedEvent
{
    /**
     * @var ChannelId
     *
     * @JMS\Type("Ergonode\Workflow\Domain\Entity\ChannelId")
     */
    private ChannelId $id;

    /**
     * @param ChannelId          $id
     * @param TranslatableString $from
     * @param TranslatableString $to
     */
    public function __construct(ChannelId $id, TranslatableString $from, TranslatableString $to)
    {
        parent::__construct($from, $to);

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
