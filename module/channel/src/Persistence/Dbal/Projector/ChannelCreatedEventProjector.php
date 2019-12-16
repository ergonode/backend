<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Channel\Domain\Event\ChannelCreatedEvent;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;

/**
 */
class ChannelCreatedEventProjector implements DomainEventProjectorInterface
{
    private const TABLE = 'exporter.channel';

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
    public function supports(DomainEventInterface $event): bool
    {
        return $event instanceof ChannelCreatedEvent;
    }

    /**
     * @param AbstractId                               $aggregateId
     * @param DomainEventInterface|ChannelCreatedEvent $event
     *
     * @throws \Throwable
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$this->supports($event)) {
            throw new UnsupportedEventException($event, ChannelCreatedEvent::class);
        }

        $this->connection->transactional(function () use ($aggregateId, $event) {
            $this->connection->insert(
                self::TABLE,
                [
                    'id' => $event->getId()->getValue(),
                    'name' => $event->getName(),
                ]
            );

            $this->connection->commit();
        });
    }
}
