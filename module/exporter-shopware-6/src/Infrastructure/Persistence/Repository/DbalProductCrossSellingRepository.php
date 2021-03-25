<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Persistence\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Types\Types;
use Ergonode\ExporterShopware6\Domain\Repository\ProductCrossSellingRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

class DbalProductCrossSellingRepository implements ProductCrossSellingRepositoryInterface
{
    private const TABLE = 'exporter.shopware6_product_collection';
    private const FIELDS = [
        'channel_id',
        'product_collection_id',
        'product_id',
        'shopware6_id',
    ];

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function load(ChannelId $channelId, ProductCollectionId $productCollectionId, ProductId $productId): ?string
    {
        $query = $this->connection->createQueryBuilder();
        $record = $query
            ->select(self::FIELDS)
            ->from(self::TABLE, 'pc')
            ->where($query->expr()->eq('pc.channel_id', ':channelId'))
            ->setParameter(':channelId', $channelId->getValue())
            ->andWhere($query->expr()->eq('pc.product_collection_id', ':productCollectionId'))
            ->setParameter(':productCollectionId', $productCollectionId->getValue())
            ->andWhere($query->expr()->eq('pc.product_id', ':productId'))
            ->setParameter(':productId', $productId->getValue())
            ->execute()
            ->fetch();

        if ($record) {
            return $record['shopware6_id'];
        }

        return null;
    }

    /**
     * @throws DBALException
     */
    public function save(
        ChannelId $channelId,
        ProductCollectionId $productCollectionId,
        ProductId $productId,
        string $shopwareId
    ): void {
        $sql = 'INSERT INTO '.self::TABLE.' (channel_id, product_collection_id, product_id, shopware6_id, updated_at) 
        VALUES (:channelId, :productCollectionId, :productId, :shopware6Id, :updatedAt)
            ON CONFLICT ON CONSTRAINT shopware6_product_collection_pkey
                DO UPDATE SET shopware6_id = :shopware6Id, updated_at = :updatedAt
        ';

        $this->connection->executeQuery(
            $sql,
            [
                'channelId' => $channelId->getValue(),
                'productCollectionId' => $productCollectionId->getValue(),
                'productId' => $productId->getValue(),
                'shopware6Id' => $shopwareId,
                'updatedAt' => new \DateTimeImmutable(),
            ],
            [
                'updatedAt' => Types::DATETIMETZ_MUTABLE,
            ]
        );
    }

    public function exists(ChannelId $channelId, ProductCollectionId $productCollectionId, ProductId $productId): bool
    {
        $query = $this->connection->createQueryBuilder();
        $result = $query->select(1)
            ->from(self::TABLE, 'pc')
            ->where($query->expr()->eq('pc.channel_id', ':channelId'))
            ->setParameter(':channelId', $channelId->getValue())
            ->andWhere($query->expr()->eq('pc.product_collection_id', ':productCollectionId'))
            ->setParameter(':productCollectionId', $productCollectionId->getValue())
            ->andWhere($query->expr()->eq('pc.product_id', ':productId'))
            ->setParameter(':productId', $productId->getValue())
            ->execute()
            ->rowCount();

        if ($result) {
            return true;
        }

        return false;
    }

    /**
     * @throws DBALException
     */
    public function delete(ChannelId $channelId, string $shopwareId): void
    {
        $this->connection->delete(
            self::TABLE,
            [
                'shopware6_id' => $shopwareId,
                'channel_id' => $channelId->getValue(),
            ]
        );
    }
}
