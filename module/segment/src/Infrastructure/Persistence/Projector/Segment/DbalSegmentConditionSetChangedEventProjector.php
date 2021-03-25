<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Infrastructure\Persistence\Projector\Segment;

use Ergonode\Segment\Domain\Event\SegmentConditionSetChangedEvent;
use Ergonode\Segment\Infrastructure\Persistence\Projector\AbstractDbalSegmentUpdateEventProjector;

class DbalSegmentConditionSetChangedEventProjector extends AbstractDbalSegmentUpdateEventProjector
{
    private const TABLE = 'segment';

    public function __invoke(SegmentConditionSetChangedEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'condition_set_id' => $event->getTo() ? $event->getTo()->getValue() : null,
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );

        $this->update($event->getAggregateId());
    }
}
