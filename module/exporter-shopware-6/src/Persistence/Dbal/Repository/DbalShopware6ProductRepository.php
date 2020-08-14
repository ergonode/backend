<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Persistence\Dbal\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6ProductRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

/**
 */
class DbalShopware6ProductRepository implements Shopware6ProductRepositoryInterface
{
    private const TABLE = 'exporter.shopware6_product';
    private const FIELDS = [
        'channel_id',
        'product_id',
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
     * @param ProductId $productId
     *
     * @return string|null
     */
    public function load(ChannelId $channelId, ProductId $productId): ?string
    {
        $query = $this->connection->createQueryBuilder();
        $record = $query
            ->select(self::FIELDS)
            ->from(self::TABLE, 'p')
            ->where($query->expr()->eq('p.channel_id', ':channelId'))
            ->setParameter(':channelId', $channelId->getValue())
            ->andWhere($query->expr()->eq('p.product_id', ':productId'))
            ->setParameter(':productId', $productId->getValue())
            ->execute()
            ->fetch();

        if ($record) {
            return $record['shopware6_id'];
        }

        return null;
    }

    /**
     * @param ChannelId $channelId
     * @param ProductId $productId
     * @param string    $shopwareId
     *
     * @throws DBALException
     */
    public function save(ChannelId $channelId, ProductId $productId, string $shopwareId): void
    {
        if ($this->exists($channelId, $productId)) {
            $this->update($channelId, $productId, $shopwareId);
        } else {
            $this->insert($channelId, $productId, $shopwareId);
        }
    }

    /**
     * @param ChannelId $channelId
     * @param ProductId $productId
     *
     * @return bool
     */
    public function exists(ChannelId $channelId, ProductId $productId): bool
    {
        $query = $this->connection->createQueryBuilder();
        $result = $query->select(1)
            ->from(self::TABLE, 'p')
            ->where($query->expr()->eq('p.channel_id', ':channelId'))
            ->setParameter(':channelId', $channelId->getValue())
            ->andWhere($query->expr()->eq('p.product_id', ':productId'))
            ->setParameter(':productId', $productId->getValue())
            ->execute()
            ->rowCount();


        if ($result) {
            return true;
        }

        return false;
    }

    /**
     * @param ChannelId $channelId
     * @param ProductId $productId
     * @param string    $shopwareId
     *
     * @throws DBALException
     */
    private function update(ChannelId $channelId, ProductId $productId, string $shopwareId): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'shopware6_id' => $shopwareId,
                'update_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            ],
            [
                'product_id' => $productId->getValue(),
                'channel_id' => $channelId->getValue(),
            ]
        );
    }

    /**
     * @param ChannelId $channelId
     * @param ProductId $productId
     * @param string    $shopwareId
     *
     * @throws DBALException
     */
    private function insert(ChannelId $channelId, ProductId $productId, string $shopwareId): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'shopware6_id' => $shopwareId,
                'product_id' => $productId->getValue(),
                'channel_id' => $channelId->getValue(),
                'update_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            ]
        );
    }
}
