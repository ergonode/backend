<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;
use Ergonode\ExporterShopware6\Domain\Query\MultimediaQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;

class DbalMultimediaQuery implements MultimediaQueryInterface
{
    private const TABLE = 'exporter.shopware6_multimedia';
    private const FIELDS = [
        'channel_id',
        'multimedia_id',
        'shopware6_id',
    ];
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function loadByShopwareId(ChannelId $channel, string $shopwareId): ?MultimediaId
    {
        $query = $this->connection->createQueryBuilder();
        $record = $query
            ->select(self::FIELDS)
            ->from(self::TABLE, 'm')
            ->where($query->expr()->eq('channel_id', ':channelId'))
            ->setParameter(':channelId', $channel->getValue())
            ->andWhere($query->expr()->eq('shopware6_id', ':shopware6Id'))
            ->setParameter(':shopware6Id', $shopwareId)
            ->execute()
            ->fetch();

        if ($record) {
            return new MultimediaId($record['multimedia_id']);
        }

        return null;
    }

    public function cleanData(ChannelId $channel, \DateTimeImmutable $dateTime): void
    {
        $query = $this->connection->createQueryBuilder();
        $query->delete(self::TABLE, 'm')
            ->where($query->expr()->eq('m.channel_id', ':channelId'))
            ->setParameter(':channelId', $channel->getValue())
            ->andWhere($query->expr()->lt('m.update_at', ':updateAt'))
            ->setParameter(':updateAt', $dateTime, Types::DATETIMETZ_MUTABLE)
            ->execute();
    }
}
