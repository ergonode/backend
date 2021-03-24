<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Infrastructure\Persistence\Projector\Segment;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Segment\Domain\Event\SegmentCreatedEvent;
use Ergonode\SharedKernel\Application\Serializer\SerializerInterface;

class DbalSegmentCreatedEventProjector
{
    private const TABLE = 'segment';

    private Connection $connection;

    private SerializerInterface $serializer;

    public function __construct(Connection $connection, SerializerInterface $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    /**
     * @throws DBALException
     */
    public function __invoke(SegmentCreatedEvent $event): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'id' => $event->getAggregateId()->getValue(),
                'code' => $event->getCode(),
                'name' => $this->serializer->serialize($event->getName()),
                'description' => $this->serializer->serialize($event->getDescription()),
                'condition_set_id' => $event->getConditionSetId() ? $event->getConditionSetId()->getValue() : null,
            ]
        );

        $this->connection->executeQuery(
            'INSERT INTO segment_product (segment_id, product_id) SELECT ?, id FROM product',
            [$event->getAggregateId()->getValue()]
        );
    }
}
