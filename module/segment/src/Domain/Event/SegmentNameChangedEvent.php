<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Domain\Event;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Domain\Event\AbstractTranslatableStringBasedChangedEvent;
use Ergonode\Segment\Domain\Entity\SegmentId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class SegmentNameChangedEvent extends AbstractTranslatableStringBasedChangedEvent
{
    /**
     * @var SegmentId
     *
     * @JMS\Type("Ergonode\Segment\Domain\Entity\SegmentId")
     */
    private SegmentId $id;

    /**
     * @param SegmentId          $id
     * @param TranslatableString $from
     * @param TranslatableString $to
     */
    public function __construct(SegmentId $id, TranslatableString $from, TranslatableString $to)
    {
        parent::__construct($from, $to);
        $this->id = $id;
    }

    /**
     * @return SegmentId
     */
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }
}
