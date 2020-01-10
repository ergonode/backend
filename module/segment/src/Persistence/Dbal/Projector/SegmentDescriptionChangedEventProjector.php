<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Segment\Domain\Event\SegmentDescriptionChangedEvent;
use JMS\Serializer\SerializerInterface;

/**
 */
class SegmentDescriptionChangedEventProjector
{
    private const TABLE = 'segment';

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @param Connection          $connection
     * @param SerializerInterface $serializer
     */
    public function __construct(Connection $connection, SerializerInterface $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    /**
     * @param SegmentDescriptionChangedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(SegmentDescriptionChangedEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'description' => $this->serializer->serialize($event->getTo(), 'json'),
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
