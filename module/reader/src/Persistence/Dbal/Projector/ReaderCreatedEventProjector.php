<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Reader\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;
use Ergonode\Reader\Domain\Event\ReaderCreatedEvent;

/**
 */
class ReaderCreatedEventProjector implements DomainEventProjectorInterface
{
    private const TABLE = 'importer.reader';

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * {@inheritDoc}
     */
    public function support(DomainEventInterface $event): bool
    {
        return $event instanceof ReaderCreatedEvent;
    }

    /**
     * {@inheritDoc}
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$event instanceof ReaderCreatedEvent) {
            throw new UnsupportedEventException($event, ReaderCreatedEvent::class);
        }

        $this->connection->insert(
            self::TABLE,
            [
                'id' => $aggregateId->getValue(),
                'name' => $event->getName(),
                'type' => $event->getType(),
            ]
        );
    }
}
