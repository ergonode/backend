<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Infrastructure\Persistence\Projector\ConditionSet;

use Ergonode\Condition\Domain\Event\ConditionSetConditionsChangedEvent;
use Ergonode\Segment\Domain\Query\SegmentQueryInterface;
use Doctrine\DBAL\Connection;
use Ergonode\Segment\Infrastructure\Persistence\Projector\AbstractDbalSegmentUpdateEventProjector;

class DbalConditionSetConditionsChangedEventProjector extends AbstractDbalSegmentUpdateEventProjector
{
    private SegmentQueryInterface $query;

    public function __construct(Connection $connection, SegmentQueryInterface $query)
    {
        parent::__construct($connection);

        $this->query = $query;
    }

    public function __invoke(ConditionSetConditionsChangedEvent $event): void
    {
        $segmentIds = $this->query->findIdByConditionSetId($event->getAggregateId());
        foreach ($segmentIds as $segmentId) {
            $this->update($segmentId);
        }
    }
}
