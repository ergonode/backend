<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Infrastructure\Persistence\Projector\Segment;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Segment\Domain\Event\SegmentDescriptionChangedEvent;
use Ergonode\SharedKernel\Application\Serializer\SerializerInterface;

class DbalSegmentDescriptionChangedEventProjector
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
    public function __invoke(SegmentDescriptionChangedEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'description' => $this->serializer->serialize($event->getTo()),
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
