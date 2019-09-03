<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;
use Ergonode\Workflow\Domain\Event\Status\StatusCreatedEvent;
use JMS\Serializer\SerializerInterface;

/**
 */
class StatusCreatedEventProjector implements DomainEventProjectorInterface
{
    private const TABLE = 'status';

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
     * {@inheritDoc}
     */
    public function support(DomainEventInterface $event): bool
    {
        return $event instanceof StatusCreatedEvent;
    }

    /**
     * {@inheritDoc}
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$event instanceof StatusCreatedEvent) {
            throw new UnsupportedEventException($event, StatusCreatedEvent::class);
        }

        $this->connection->insert(
            self::TABLE,
            [
                'id' => $aggregateId->getValue(),
                'code' => $event->getCode(),
                'name' => $this->serializer->serialize($event->getName()->getTranslations(), 'json'),
                'description' => $this->serializer->serialize($event->getDescription()->getTranslations(), 'json'),
                'color' => $event->getColor()->getValue(),
            ]
        );
    }
}
