<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Types\Types;
use Ergonode\Channel\Domain\Query\SchedulerQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

class DbalSchedulerQuery implements SchedulerQueryInterface
{
    private const TABLE = 'exporter.scheduler';
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return array
     */
    public function getReadyToRun(\DateTime $time): array
    {
        $sub = $this->connection->createQueryBuilder();
        $sub->select('id, active, start, last, current_timestamp AS actual')
            ->addSelect(
                'current_timestamp - INTERVAL \'1 hour \' * "hour" - INTERVAL \'1 minute \' * "minute" AS expected'
            )
            ->from(self::TABLE);

        $qb = $result = $this->connection->createQueryBuilder();
        $records = $qb
            ->select('*')
            ->from(sprintf('(%s)', $sub->getSQL()), 't')
            ->andWhere($qb->expr()->lte('start', 'actual'))
            ->andWhere($qb->expr()->eq('active', ':active'))
            ->setParameter(':active', true, \PDO::PARAM_BOOL)
            ->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->isNull('last'),
                    $qb->expr()->lte('last', 'expected')
                )
            )
            ->execute()
            ->fetchAll();

        $result = [];
        foreach ($records as $record) {
            $result[] = new ChannelId($record['id']);
        }

        return $result;
    }

    /**
     * @throws DBALException
     */
    public function markAsRun(ChannelId $id, \DateTime $time): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'last' => $time,
            ],
            [
                'id' => $id->getValue(),
            ],
            [
                'last' => Types::DATETIMETZ_MUTABLE,
            ],
        );
    }
}
