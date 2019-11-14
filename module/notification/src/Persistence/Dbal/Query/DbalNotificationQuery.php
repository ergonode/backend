<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Notification\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Notification\Domain\Query\NotificationQueryInterface;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;

/**
 */
class DbalNotificationQuery implements NotificationQueryInterface
{

    private const NOTIFICATION_TABLE = 'notification';
    private const USER_NOTIFICATION_TABLE = 'users_notification';
    private const USER_TABLE = 'users';

    private const FIELDS = [
        'n.*',
        'un.read_at',
        'u.avatar_id',
        'COALESCE(u.first_name || \' \' || u.last_name, \'Deleted\') AS author',
    ];

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
     * @param UserId   $id
     * @param Language $language
     *
     * @return DataSetInterface
     */
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
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(self::FIELDS)
            ->from(self::NOTIFICATION_TABLE, 'n')
            ->join('n', self::USER_NOTIFICATION_TABLE, 'un', 'un.notification_id = n.id')
            ->leftJoin('n', self::USER_TABLE, 'u', 'u.id = n.author_id');
    }
}
