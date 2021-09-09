<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Notification\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;
use Ergonode\Notification\Domain\Query\NotificationQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ramsey\Uuid\Uuid;
use Doctrine\DBAL\DBALException;

class DbalNotificationQuery implements NotificationQueryInterface
{
    private const USER_NOTIFICATION_TABLE = 'users_notification';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return array
     */
    public function check(UserId $id): array
    {
        $query = $this->connection->createQueryBuilder();
        $result = $query->select('count(*) as count')
        ->from(self::USER_NOTIFICATION_TABLE)
        ->andWhere($query->expr()->eq('recipient_id', ':id'))
            ->andWhere($query->expr()->isNull('read_at'))
            ->setParameter(':id', $id->getValue())
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);

        return [
            'unread' => $result ?: 0,
        ];
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    public function mark(Uuid $id, UserId $userId, \DateTime $readAt): void
    {
        $this->connection->update(
            'users_notification',
            [
                'read_at' => $readAt,
            ],
            [
                'recipient_id' => $userId->getValue(),
                'notification_id' => $id->toString(),
            ],
            [
                'read_at' => Types::DATETIMETZ_MUTABLE,
            ],
        );
    }

    /**
     * @throws DBALException
     */
    public function markAll(UserId $userId, \DateTime $readAt): void
    {
        $this->connection->update(
            'users_notification',
            [
                'read_at' => $readAt,
            ],
            [
                'recipient_id' => $userId->getValue(),
            ],
            [
                'read_at' => Types::DATETIMETZ_MUTABLE,
            ],
        );
    }
}
