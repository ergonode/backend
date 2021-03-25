<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Infrastructure\Persistence\Projector\Segment;

use Doctrine\DBAL\Connection;
use Ergonode\Segment\Domain\Event\SegmentDeletedEvent;

class DbalSegmentDeletedEventProjector
{
    private const TABLE = 'segment';
    private const TABLE_PRODUCT = 'segment_product';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function __invoke(SegmentDeletedEvent $event): void
    {
        $this->connection->delete(
            self::TABLE,
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );

        $this->connection->delete(
            self::TABLE_PRODUCT,
            [
                'segment_id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
