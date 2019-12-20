<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Segment\Domain\Event\SegmentCreatedEvent;
use Ergonode\Segment\Domain\ValueObject\SegmentStatus;
use JMS\Serializer\SerializerInterface;

/**
 */
class SegmentCreatedEventProjector
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
     * @param SegmentCreatedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(SegmentCreatedEvent $event): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'id' => $event->getAggregateId()->getValue(),
                'code' => $event->getCode(),
                'name' => $this->serializer->serialize($event->getName(), 'json'),
                'description' => $this->serializer->serialize($event->getDescription(), 'json'),
                'status' => SegmentStatus::NEW,
                'condition_set_id' => $event->getConditionSetId() ? $event->getConditionSetId()->getValue() : null,
            ]
        );
    }
}
