<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;
use Ergonode\ExporterShopware6\Domain\Query\CustomFieldQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

class DbalCustomFiledQuery implements CustomFieldQueryInterface
{
    private const TABLE = 'exporter.shopware6_custom_field';
    private const FIELDS = [
        'channel_id',
        'attribute_id',
        'shopware6_id',
    ];

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function loadByShopwareId(ChannelId $channel, string $shopwareId): ?AttributeId
    {
        $query = $this->connection->createQueryBuilder();
        $record = $query
            ->select(self::FIELDS)
            ->from(self::TABLE, 'cf')
            ->where($query->expr()->eq('channel_id', ':channelId'))
            ->setParameter(':channelId', $channel->getValue())
            ->andWhere($query->expr()->eq('shopware6_id', ':shopware6Id'))
            ->setParameter(':shopware6Id', $shopwareId)
            ->execute()
            ->fetch();

        if ($record) {
            return new AttributeId($record['attribute_id']);
        }

        return null;
    }

    public function cleanData(ChannelId $channel, \DateTimeImmutable $dateTime, string $type): void
    {
        $query = $this->connection->createQueryBuilder();
        $query->delete(self::TABLE, 'cf')
            ->where($query->expr()->eq('channel_id', ':channelId'))
            ->setParameter(':channelId', $channel->getValue())
            ->andWhere($query->expr()->lt('cf.update_at', ':updateAt'))
            ->setParameter(':updateAt', $dateTime, Types::DATETIMETZ_MUTABLE)
            ->andWhere($query->expr()->eq('cf.type', ':type'))
            ->setParameter(':type', $type)
            ->execute();
    }
}
