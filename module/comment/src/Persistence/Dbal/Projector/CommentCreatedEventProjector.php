<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Types\Types;
use Ergonode\Comment\Domain\Event\CommentCreatedEvent;

/**
 */
class CommentCreatedEventProjector
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
     * @param CommentCreatedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(CommentCreatedEvent $event): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'id' => $event->getAggregateId()->getValue(),
                'author_id' => $event->getAuthorId()->getValue(),
                'object_id' => $event->getObjectId()->toString(),
                'created_at' => $event->getCreatedAt(),
                'content' => $event->getContent(),
            ],
            [
                'created_at' => Types::DATETIMETZ_MUTABLE,
            ],
        );
    }
}
