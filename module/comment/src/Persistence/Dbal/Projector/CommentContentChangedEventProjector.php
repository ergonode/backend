<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;
use Ergonode\Comment\Domain\Event\CommentContentChangedEvent;

/**
 */
class CommentContentChangedEventProjector
{
    private const TABLE = 'comment';

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param CommentContentChangedEvent $event
     *
     * @throws \Throwable
     */
    public function __invoke(CommentContentChangedEvent $event): void
    {
        $this->connection->transactional(function () use ($event) {
            $this->connection->update(
                self::TABLE,
                [
                    'edited_at' => $event->getEditedAt(),
                    'content' => $event->getTo(),
                ],
                [
                    'id' => $event->getAggregateId()->getValue(),
                ],
                [
                    'edited_at' => Types::DATETIMETZ_MUTABLE,
                ],
            );
        });
    }
}
