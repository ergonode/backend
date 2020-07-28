<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Ergonode\ExporterShopware6\Domain\Query\Shopware6LanguageQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

/**
 */
class DbalShopware6LanguageQuery implements Shopware6LanguageQueryInterface
{
    private const TABLE = 'exporter.shopware6_language';

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
     * @param ChannelId          $channelId
     * @param \DateTimeImmutable $dateTime
     */
    public function cleanData(ChannelId $channelId, \DateTimeImmutable $dateTime): void
    {
        $query = $this->connection->createQueryBuilder();
        $query->delete(self::TABLE, 'cf')
            ->where($query->expr()->eq('channel_id', ':channelId'))
            ->setParameter(':channelId', $channelId->getValue())
            ->andWhere($query->expr()->lt('cf.update_at', ':updateAt'))
            ->setParameter(':updateAt', $dateTime->format('Y-m-d H:i:s'))
            ->execute();
    }
}
