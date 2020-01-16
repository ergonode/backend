<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Comment\Domain\Event\CommentContentChangedEvent;

/**
 */
class CommentContentChangedEventProjector
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
                    'edited_at' => $event->getEditedAt()->format('Y-m-d H:i:s'),
                    'content' => $event->getTo(),
                ],
                [
                    'id' => $event->getAggregateId()->getValue(),
                ]
            );
        });
    }
}
