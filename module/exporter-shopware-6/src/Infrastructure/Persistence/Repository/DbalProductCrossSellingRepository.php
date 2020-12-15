<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
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

    public function save(
        ChannelId $channelId,
        ProductCollectionId $productCollectionId,
        ProductId $productId,
        string $shopwareId
    ): void {
        if ($this->exists($channelId, $productCollectionId, $productId)) {
            $this->update($channelId, $productCollectionId, $productId, $shopwareId);
        } else {
            $this->insert($channelId, $productCollectionId, $productId, $shopwareId);
        }
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
    private function update(
        ChannelId $channelId,
        ProductCollectionId $productCollectionId,
        ProductId $productId,
        string $shopwareId
    ): void {
        $this->connection->update(
            self::TABLE,
            [
                'shopware6_id' => $shopwareId,
                'updated_at' => new \DateTimeImmutable(),
            ],
            [
                'product_collection_id' => $productCollectionId->getValue(),
                'product_id' => $productId->getValue(),
                'channel_id' => $channelId->getValue(),
            ],
            [
                'updated_at' => Types::DATETIMETZ_MUTABLE,
            ],
        );
    }

    /**
     * @throws DBALException
     */
    private function insert(
        ChannelId $channelId,
        ProductCollectionId $productCollectionId,
        ProductId $productId,
        string $shopwareId
    ): void {
        $this->connection->insert(
            self::TABLE,
            [
                'shopware6_id' => $shopwareId,
                'product_collection_id' => $productCollectionId->getValue(),
                'product_id' => $productId->getValue(),
                'channel_id' => $channelId->getValue(),
                'updated_at' => new \DateTimeImmutable(),
            ],
            [
                'updated_at' => Types::DATETIMETZ_MUTABLE,
            ],
        );
    }
}
