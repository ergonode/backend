<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Notification\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Types;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;
use Ergonode\Notification\Domain\Query\NotificationQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ramsey\Uuid\Uuid;

class DbalNotificationQuery implements NotificationQueryInterface
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

    public function getDataSet(UserId $id, Language $language): DataSetInterface
    {
        $qb = $this->getQuery();
        $qb->where($qb->expr()->eq('recipient_id', ':user_id'));

        $result = $this->connection->createQueryBuilder();
        $result->select('*');
        $result->from(sprintf('(%s)', $qb->getSQL()), 't');
        $result->setParameter('user_id', $id->getValue());

        return new DbalDataSet($result);
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

    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(self::FIELDS)
            ->from(self::NOTIFICATION_TABLE, 'n')
            ->join('n', self::USER_NOTIFICATION_TABLE, 'un', 'un.notification_id = n.id')
            ->leftJoin('n', self::USER_TABLE, 'u', 'u.id = n.author_id');
    }
}
