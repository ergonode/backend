<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Comment\Infrastructure\Persistence\Projector;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;
use Ergonode\Comment\Domain\Event\CommentContentChangedEvent;

class DbalCommentContentChangedEventProjector
{
    private const TABLE = 'comment';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws \Throwable
     */
    public function __invoke(CommentContentChangedEvent $event): void
    {
        $this->connection->transactional(function () use ($event): void {
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
