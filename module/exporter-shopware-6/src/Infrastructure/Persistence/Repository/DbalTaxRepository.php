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
use Ergonode\ExporterShopware6\Domain\Repository\TaxRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

class DbalTaxRepository implements TaxRepositoryInterface
{
    private const TABLE = 'exporter.shopware6_tax';
    private const FIELDS = [
        'channel_id',
        'tax',
        'shopware6_id',
    ];

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function load(ChannelId $channelId, float $tax): ?string
    {
        $query = $this->connection->createQueryBuilder();
        $record = $query
            ->select(self::FIELDS)
            ->from(self::TABLE, 't')
            ->where($query->expr()->eq('channel_id', ':channelId'))
            ->setParameter(':channelId', $channelId->getValue())
            ->andWhere($query->expr()->eq('t.tax', ':tax'))
            ->setParameter(':tax', $tax)
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
    public function save(ChannelId $channelId, float $tax, string $shopwareId): void
    {
        $sql = 'INSERT INTO '.self::TABLE.' (channel_id, tax, shopware6_id, update_at) 
        VALUES (:channelId, :tax, :shopware6Id, :updatedAt)
            ON CONFLICT ON CONSTRAINT shopware6_tax_pkey
                DO UPDATE SET shopware6_id = :shopware6Id, update_at = :updatedAt
        ';

        $this->connection->executeQuery(
            $sql,
            [
                'channelId' => $channelId->getValue(),
                'tax' => $tax,
                'shopware6Id' => $shopwareId,
                'updatedAt' => new \DateTimeImmutable(),
            ],
            [
                'updatedAt' => Types::DATETIMETZ_MUTABLE,
            ]
        );
    }

    public function exists(
        ChannelId $channelId,
        float $tax
    ): bool {
        $query = $this->connection->createQueryBuilder();
        $result = $query->select(1)
            ->from(self::TABLE)
            ->where($query->expr()->eq('channel_id', ':channelId'))
            ->setParameter(':channelId', $channelId->getValue())
            ->andWhere($query->expr()->eq('tax', ':tax'))
            ->setParameter(':tax', $tax)
            ->execute()
            ->rowCount();


        if ($result) {
            return true;
        }

        return false;
    }
}
