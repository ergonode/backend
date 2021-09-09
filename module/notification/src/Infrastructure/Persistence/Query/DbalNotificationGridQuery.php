<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Notification\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Notification\Domain\Query\NotificationGridQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;

class DbalNotificationGridQuery implements NotificationGridQueryInterface
{
    private const NOTIFICATION_TABLE = 'notification';
    private const USER_NOTIFICATION_TABLE = 'users_notification';
    private const USER_TABLE = 'users';

    private const FIELDS = [
        'n.*',
        'un.read_at',
        'u.id AS user_id',
        'CASE WHEN u.avatar THEN u.id ELSE null END as avatar_filename',
        'COALESCE(u.first_name || \' \' || u.last_name, \'Deleted\') AS author',
    ];

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getGridQuery(UserId $id, Language $language): QueryBuilder
    {
        $qb = $this->connection->createQueryBuilder();

        return $qb
            ->select(self::FIELDS)
            ->from(self::NOTIFICATION_TABLE, 'n')
            ->join('n', self::USER_NOTIFICATION_TABLE, 'un', 'un.notification_id = n.id')
            ->leftJoin('n', self::USER_TABLE, 'u', 'u.id = n.author_id')
            ->where($qb->expr()->eq('recipient_id', ':user_id'))
            ->setParameter('user_id', $id->getValue());
    }
}
