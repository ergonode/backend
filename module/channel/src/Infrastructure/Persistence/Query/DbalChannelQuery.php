<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Channel\Domain\Query\ChannelQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\Factory\DbalDataSetFactory;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

class DbalChannelQuery implements ChannelQueryInterface
{
    private const CHANNEL_TABLE = 'exporter.channel';

    private Connection $connection;

    private DbalDataSetFactory $dataSetFactory;

    public function __construct(Connection $connection, DbalDataSetFactory $dataSetFactory)
    {
        $this->connection = $connection;
        $this->dataSetFactory = $dataSetFactory;
    }

    public function getDataSet(Language $language): DataSetInterface
    {
        return $this->dataSetFactory->create($this->getQuery());
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


    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('ch.id, ch.name, ch.type')
            ->from(self::CHANNEL_TABLE, 'ch');
    }
}
