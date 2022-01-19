<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Ergonode\Channel\Domain\Query\ChannelQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;

class DbalChannelQuery implements ChannelQueryInterface
{
    private const CHANNEL_TABLE = 'exporter.channel';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findChannelIdsByType(string $type): array
    {
        $qb = $this->connection->createQueryBuilder();

        $data = $qb
            ->select('id')
            ->from(self::CHANNEL_TABLE)
            ->where($qb->expr()->eq('type', ':type'))
            ->setParameter(':type', $type)
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);

        $result = [];
        foreach ($data as $channelId) {
            $result[] = new ChannelId($channelId);
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function findIdBySegmentId(SegmentId $segmentId): array
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->select('id')
            ->from(self::CHANNEL_TABLE)
            ->where('configuration ->> \'segment_id\' = :id')
            ->setParameter(':id', $segmentId->getValue());
        $result = $queryBuilder->execute()->fetchAllAssociative();

        foreach ($result as &$item) {
            $item = new ChannelId($item['id']);
        }

        return $result;
    }
}
