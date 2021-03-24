<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Ergonode\ExporterShopware6\Domain\Query\ProductCrossSellingQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;

class DbalProductCrossSellingQuery implements ProductCrossSellingQueryInterface
{
    private const TABLE = 'exporter.shopware6_product_collection';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getOthersElements(
        ChannelId $channelId,
        ProductCollectionId $productCollectionId,
        array $productIds
    ): array {
        $query = $this->connection->createQueryBuilder();
        $query
            ->select('pc.shopware6_id, pc.product_id')
            ->from(self::TABLE, 'pc')
            ->where($query->expr()->eq('pc.channel_id', ':channelId'))
            ->setParameter(':channelId', $channelId->getValue())
            ->andWhere($query->expr()->eq('pc.product_collection_id', ':productCollectionId'))
            ->setParameter(':productCollectionId', $productCollectionId->getValue());

        if ($productIds) {
            $query->andWhere($query->expr()->notIn('pc.product_id', ':productIds'))
                ->setParameter(':productIds', $productIds, Connection::PARAM_STR_ARRAY);
        }

        return $query->execute()->fetchAll();
    }

    /**
     * @param ProductCollectionId[] $productCollectionIds
     *
     * @return string[]
     */
    public function getOthersCollection(ChannelId $channelId, array $productCollectionIds): array
    {
        $query = $this->connection->createQueryBuilder();
        $query
            ->select('DISTINCT pc.shopware6_id')
            ->from(self::TABLE, 'pc')
            ->where($query->expr()->eq('pc.channel_id', ':channelId'))
            ->setParameter(':channelId', $channelId->getValue());

        if ($productCollectionIds) {
            $query->andWhere($query->expr()->notIn('pc.product_collection_id', ':productCollectionId'))
                ->setParameter(':productCollectionId', $productCollectionIds, Connection::PARAM_STR_ARRAY);
        }

        return $query->execute()->fetchAll(\PDO::FETCH_COLUMN);
    }
}
