<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Persistence\Dbal\Repository;

use Doctrine\DBAL\Connection;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6LanguageRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

/**
 */
class DbalShopware6LanguageRepository implements Shopware6LanguageRepositoryInterface
{
    private const TABLE = 'exporter.shopware6_language';
    private const FIELDS = [
        'channel_id',
        'shopware6_id',
        'name',
        'locale_id',
    ];

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
     * @param ChannelId $channelId
     * @param string    $shopwareId
     *
     * @return string|null
     */
    public function load(ChannelId $channelId, string $shopwareId): ?string
    {
        $query = $this->connection->createQueryBuilder();

        return $query
            ->select(self::FIELDS)
            ->from(self::TABLE, 'c')
            ->where($query->expr()->eq('channel_id', ':channelId'))
            ->setParameter(':channelId', $channelId->getValue())
            ->andWhere($query->expr()->eq('c.shopware6_id', ':shopwareId'))
            ->setParameter(':shopwareId', $shopwareId)
            ->execute()
            ->fetch();
    }

    /**
     * @param ChannelId $channelId
     * @param string    $shopwareId
     * @param string    $name
     * @param string    $localeId
     */
    public function save(ChannelId $channelId, string $shopwareId, string $name, string $localeId): void
    {
        if ($this->exists($channelId, $shopwareId)) {
            $this->update($channelId, $shopwareId, $name, $localeId);
        } else {
            $this->insert($channelId, $shopwareId, $name, $localeId);
        }
    }

    /**
     * @param ChannelId $channelId
     * @param string    $shopwareId
     *
     * @return bool
     */
    public function exists(ChannelId $channelId, string $shopwareId): bool
    {
        $query = $this->connection->createQueryBuilder();
        $result = $query->select(1)
            ->from(self::TABLE)
            ->where($query->expr()->eq('channel_id', ':channelId'))
            ->setParameter(':channelId', $channelId->getValue())
            ->andWhere($query->expr()->eq('shopware6_id', ':shopwareId'))
            ->setParameter(':shopwareId', $shopwareId)
            ->execute()
            ->rowCount();

        if ($result) {
            return true;
        }

        return false;
    }

    /**
     * @param ChannelId $channelId
     * @param string    $shopwareId
     * @param string    $name
     * @param string    $localeId
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function update(ChannelId $channelId, string $shopwareId, string $name, string $localeId): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'shopware6_id' => $shopwareId,
                'update_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
                'name' => $name,
                'locale_id' => $localeId,
            ],
            [
                'shopware6_id' => $shopwareId,
                'channel_id' => $channelId->getValue(),
            ]
        );
    }

    /**
     * @param ChannelId $channelId
     * @param string    $shopwareId
     * @param string    $name
     * @param string    $localeId
     */
    private function insert(ChannelId $channelId, string $shopwareId, string $name, string $localeId): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'channel_id' => $channelId->getValue(),
                'shopware6_id' => $shopwareId,
                'name' => $name,
                'locale_id' => $localeId,
                'update_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            ]
        );
    }
}
