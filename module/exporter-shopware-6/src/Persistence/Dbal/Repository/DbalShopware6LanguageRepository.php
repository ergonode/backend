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
        'name',
        'shopware6_id',
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
     * @param string    $name
     *
     * @return string|null
     */
    public function load(ChannelId $channelId, string $name): ?string
    {
        $query = $this->connection->createQueryBuilder();

        return $query
            ->select(self::FIELDS)
            ->from(self::TABLE, 'c')
            ->where($query->expr()->eq('channel_id', ':channelId'))
            ->setParameter(':channelId', $channelId->getValue())
            ->andWhere($query->expr()->eq('c.name', ':name'))
            ->setParameter(':name', $name)
            ->execute()
            ->fetch();
    }

    /**
     * @param ChannelId $channelId
     * @param string    $name
     * @param string    $shopwareId
     */
    public function save(ChannelId $channelId, string $name, string $shopwareId): void
    {
        if ($this->exists($channelId, $name)) {
            $this->update($channelId, $name, $shopwareId);
        } else {
            $this->insert($channelId, $name, $shopwareId);
        }
    }

    /**
     * @param ChannelId $channelId
     * @param string    $name
     *
     * @return bool
     */
    public function exists(ChannelId $channelId, string $name): bool
    {
        $query = $this->connection->createQueryBuilder();
        $result = $query->select(1)
            ->from(self::TABLE)
            ->where($query->expr()->eq('channel_id', ':channelId'))
            ->setParameter(':channelId', $channelId->getValue())
            ->andWhere($query->expr()->eq('name', ':name'))
            ->setParameter(':name', $name)
            ->execute()
            ->rowCount();

        if ($result) {
            return true;
        }

        return false;
    }

    /**
     * @param ChannelId $channelId
     * @param string    $name
     * @param string    $shopwareId
     */
    private function update(ChannelId $channelId, string $name, string $shopwareId): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'shopware6_id' => $shopwareId,
                'update_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            ],
            [
                'name' => $name,
                'channel_id' => $channelId->getValue(),
            ]
        );
    }

    /**
     * @param ChannelId $channelId
     * @param string    $name
     * @param string    $shopwareId
     */
    private function insert(ChannelId $channelId, string $name, string $shopwareId): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'shopware6_id' => $shopwareId,
                'name' => $name,
                'channel_id' => $channelId->getValue(),
                'update_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            ]
        );
    }
}
