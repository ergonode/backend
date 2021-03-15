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
use Ergonode\ExporterShopware6\Domain\Repository\ProductRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

class DbalProductRepository implements ProductRepositoryInterface
{
    private const TABLE = 'exporter.shopware6_product';
    private const FIELDS = [
        'channel_id',
        'product_id',
        'shopware6_id',
    ];

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

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
     * @throws DBALException
     */
    public function save(ChannelId $channelId, ProductId $productId, string $shopwareId): void
    {
        $sql = 'INSERT INTO '.self::TABLE.' (channel_id, product_id, shopware6_id, update_at) 
        VALUES (:channelId, :productId, :shopware6Id, :updatedAt)
            ON CONFLICT ON CONSTRAINT shopware6_product_pkey
                DO UPDATE SET shopware6_id = :shopware6Id, update_at = :updatedAt
        ';

        $this->connection->executeQuery(
            $sql,
            [
                'channelId' => $channelId->getValue(),
                'productId' => $productId->getValue(),
                'shopware6Id' => $shopwareId,
                'updatedAt' => new \DateTimeImmutable(),
            ],
            [
                'updatedAt' => Types::DATETIMETZ_MUTABLE,
            ]
        );
    }

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
}
