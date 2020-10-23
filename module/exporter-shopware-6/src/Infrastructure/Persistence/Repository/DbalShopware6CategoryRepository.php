<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Persistence\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6CategoryRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

class DbalShopware6CategoryRepository implements Shopware6CategoryRepositoryInterface
{
    private const TABLE = 'exporter.shopware6_category';
    private const FIELDS = [
        'channel_id',
        'category_id',
        'shopware6_id',
    ];

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function load(ChannelId $channelId, CategoryId $categoryId): ?string
    {
        $query = $this->connection->createQueryBuilder();
        $record = $query
            ->select(self::FIELDS)
            ->from(self::TABLE, 'cs')
            ->where($query->expr()->eq('channel_id', ':channelId'))
            ->setParameter(':channelId', $channelId->getValue())
            ->andWhere($query->expr()->eq('cs.category_id', ':categoryId'))
            ->setParameter(':categoryId', $categoryId->getValue())
            ->execute()
            ->fetch();

        if ($record) {
            return ($record['shopware6_id']);
        }

        return null;
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    public function save(ChannelId $channelId, CategoryId $categoryId, string $shopwareId): void
    {
        if ($this->exists($channelId, $categoryId)) {
            $this->update($channelId, $categoryId, $shopwareId);
        } else {
            $this->insert($channelId, $categoryId, $shopwareId);
        }
    }

    public function exists(
        ChannelId $channelId,
        CategoryId $categoryId
    ): bool {
        $query = $this->connection->createQueryBuilder();
        $result = $query->select(1)
            ->from(self::TABLE)
            ->where($query->expr()->eq('channel_id', ':channelId'))
            ->setParameter(':channelId', $channelId->getValue())
            ->andWhere($query->expr()->eq('category_id', ':categoryId'))
            ->setParameter(':categoryId', $categoryId->getValue())
            ->execute()
            ->rowCount();


        if ($result) {
            return true;
        }

        return false;
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Exception\InvalidArgumentException
     */
    public function delete(ChannelId $channelId, CategoryId $categoryId): void
    {
        $this->connection->delete(
            self::TABLE,
            [
                'category_id' => $categoryId->getValue(),
                'channel_id' => $channelId->getValue(),
            ]
        );
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    private function update(ChannelId $channelId, CategoryId $categoryId, string $shopwareId): void
    {
        $now = new \DateTimeImmutable();
        $this->connection->update(
            self::TABLE,
            [
                'shopware6_id' => $shopwareId,
                'update_at' => $now,
            ],
            [
                'category_id' => $categoryId->getValue(),
                'channel_id' => $channelId->getValue(),
            ],
            [
                'update_at' => Types::DATETIMETZ_MUTABLE,
            ],
        );
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    private function insert(ChannelId $channelId, CategoryId $categoryId, string $shopwareId): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'shopware6_id' => $shopwareId,
                'category_id' => $categoryId->getValue(),
                'channel_id' => $channelId->getValue(),
                'update_at' => new \DateTimeImmutable(),
            ],
            [
                'update_at' => Types::DATETIMETZ_MUTABLE,
            ],
        );
    }
}
