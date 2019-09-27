<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;
use Ergonode\Segment\Domain\Event\SegmentCreatedEvent;
use Ergonode\Segment\Domain\ValueObject\SegmentStatus;
use JMS\Serializer\SerializerInterface;

/**
 */
class SegmentCreatedEventProjector implements DomainEventProjectorInterface
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
     * @param DomainEventInterface $event
     *
     * @return bool
     */
    public function supports(DomainEventInterface $event): bool
    {
        return $event instanceof SegmentCreatedEvent;
    }

    /**
     * @param AbstractId           $aggregateId
     * @param DomainEventInterface $event
     *
     * @throws UnsupportedEventException
     * @throws DBALException
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$this->supports($event)) {
            throw new UnsupportedEventException($event, SegmentCreatedEvent::class);
        }

        $this->connection->insert(
            self::TABLE,
            [
                'id' => $event->getId()->getValue(),
                'code' => $event->getCode(),
                'name' => $this->serializer->serialize($event->getName(), 'json'),
                'description' => $this->serializer->serialize($event->getDescription(), 'json'),
                'status' => SegmentStatus::NEW,
                'condition_set_id' => $event->getConditionSetId()->getValue(),
            ]
        );
    }
}
