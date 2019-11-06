<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Note\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;
use Ergonode\Note\Domain\Event\NoteContentChangedEvent;
use Ergonode\Note\Domain\Event\NoteCreatedEvent;

/**
 */
class NoteContentChangedEventProjector implements DomainEventProjectorInterface
{
    private const TABLE = 'note';

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
        return $event instanceof NoteContentChangedEvent;
    }

    /**
     * @param AbstractId                                   $aggregateId
     * @param DomainEventInterface|NoteContentChangedEvent $event
     *
     * @throws UnsupportedEventException
     * @throws \Throwable
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$this->supports($event)) {
            throw new UnsupportedEventException($event, NoteContentChangedEvent::class);
        }

        $this->connection->transactional(function () use ($aggregateId, $event) {
            $this->connection->update(
                self::TABLE,
                [
                    'edited_at' => $event->getEditedAt()->format('Y-m-d H:i:s'),
                    'content' => $event->getTo(),
                ],
                [
                    'id' => $aggregateId->getValue(),
                ]
            );
        });
    }
}
