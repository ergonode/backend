<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Ergonode\Exporter\Domain\Entity\Catalog\ExportCategory;
use Ergonode\ExporterShopware6\Domain\Entity\Catalog\Shopware6Category;
use Ergonode\ExporterShopware6\Domain\Query\Shopware6CategoryQueryInterface;
use JMS\Serializer\SerializerInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

/**
 */
class DbalShopware6CategoryQuery implements Shopware6CategoryQueryInterface
{
    private const TABLE = 'exporter.shopware6_category';
    private const TABLE_CATEGORY = 'exporter.category';
    private const FIELDS = [
        'channel_id',
        'c.category_id',
        'cs.shopware6_id',
    ];

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @param Connection          $connection
     * @param SerializerInterface $serializer
     */
    public function __construct(Connection $connection, SerializerInterface $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    /**
     * @param ChannelId $channel
     * @param string    $shopwareId
     *
     * @return Shopware6Category|null
     */
    public function loadByShopwareId(ChannelId $channel, string $shopwareId): ?Shopware6Category
    {
        $query = $this->connection->createQueryBuilder();
        $record = $query
            ->select('*')
            ->from(self::TABLE, 'cs')
            ->leftJoin('cs', self::TABLE_CATEGORY, 'c', 'c.id = cs.category_id')
            ->where($query->expr()->eq('channel_id', ':channelId'))
            ->setParameter(':channelId', $channel->getValue())
            ->andWhere($query->expr()->eq('cs.shopware6_id', ':shopware6Id'))
            ->setParameter(':shopware6Id', $shopwareId)
            ->execute()
            ->fetch();

        if ($record) {
            return new Shopware6Category(
                $record['shopware6_id'],
                $this->serializer->deserialize($record['data'], ExportCategory::class, 'json')
            );
        }

        return null;
    }

    /**
     * @param ChannelId          $channel
     * @param \DateTimeImmutable $dateTime
     */
    public function clearBefore(ChannelId $channel, \DateTimeImmutable $dateTime): void
    {
        $query = $this->connection->createQueryBuilder();
        $query->delete(self::TABLE, 'cs')
            ->where($query->expr()->eq('channel_id', ':channelId'))
            ->setParameter(':channelId', $channel->getValue())
            ->andWhere($query->expr()->lt('cs.update_at', ':updateAt'))
            ->setParameter(':updateAt', $dateTime->format('Y-m-d H:i:s'))
            ->execute();
    }
}
