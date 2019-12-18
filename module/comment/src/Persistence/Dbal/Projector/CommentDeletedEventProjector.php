<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\Comment\Domain\Event\CommentDeletedEvent;

/**
 */
class CommentDeletedEventProjector
{
    private const TABLE = 'comment';

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
        return $event instanceof CommentDeletedEvent;
    }

    /**
     * @param CommentDeletedEvent $event
     *
     * @throws \Throwable
     */
    public function __invoke(CommentDeletedEvent $event): void
    {
        if (!$this->supports($event)) {
            throw new UnsupportedEventException($event, CommentDeletedEvent::class);
        }

        $this->connection->transactional(function () use ($event) {
            $this->connection->delete(
                self::TABLE,
                [
                    'id' => $event->getAggregateId()->getValue(),
                ]
            );
        });
    }
}
