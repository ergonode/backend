<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Domain\Event;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Core\Domain\Event\AbstractTranslatableStringBasedChangedEvent;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use JMS\Serializer\Annotation as JMS;

class SegmentDescriptionChangedEvent extends AbstractTranslatableStringBasedChangedEvent
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\SegmentId")
     */
    private SegmentId $id;

    public function __construct(SegmentId $id, TranslatableString $to)
    {
        parent::__construct($to);
        $this->id = $id;
    }

    public function getAggregateId(): SegmentId
    {
        return $this->id;
    }
}
