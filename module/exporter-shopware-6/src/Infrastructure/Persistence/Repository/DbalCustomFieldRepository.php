<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Persistence\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;
use Ergonode\ExporterShopware6\Domain\Repository\CustomFieldRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

class DbalCustomFieldRepository implements CustomFieldRepositoryInterface
{
    private const TABLE = 'exporter.shopware6_custom_field';
    private const FIELDS = [
        'channel_id',
        'attribute_id',
        'shopware6_id',
    ];

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function load(ChannelId $channelId, AttributeId $attributeId): ?string
    {
        $query = $this->connection->createQueryBuilder();
        $record = $query
            ->select(self::FIELDS)
            ->from(self::TABLE, 'cf')
            ->where($query->expr()->eq('channel_id', ':channelId'))
            ->setParameter(':channelId', $channelId->getValue())
            ->andWhere($query->expr()->eq('cf.attribute_id', ':attributeId'))
            ->setParameter(':attributeId', $attributeId->getValue())
            ->execute()
            ->fetch();

        if ($record) {
            return $record['shopware6_id'];
        }

        return null;
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    public function save(ChannelId $channelId, AttributeId $attributeId, string $shopwareId, string $type): void
    {
        $sql = 'INSERT INTO '.self::TABLE.' (channel_id, attribute_id, type, shopware6_id, update_at) 
        VALUES (:channelId, :attributeId, :type, :shopware6Id, :updatedAt)
            ON CONFLICT ON CONSTRAINT shopware6_custom_field_pkey
                DO UPDATE SET shopware6_id = :shopware6Id, update_at = :updatedAt
        ';

        $this->connection->executeQuery(
            $sql,
            [
                'channelId' => $channelId->getValue(),
                'attributeId' => $attributeId->getValue(),
                'type' => $type,
                'shopware6Id' => $shopwareId,
                'updatedAt' => new \DateTimeImmutable(),
            ],
            [
                'updatedAt' => Types::DATETIMETZ_MUTABLE,
            ]
        );
    }

    public function exists(ChannelId $channelId, AttributeId $attributeId): bool
    {
        $query = $this->connection->createQueryBuilder();
        $result = $query->select(1)
            ->from(self::TABLE)
            ->where($query->expr()->eq('channel_id', ':channelId'))
            ->setParameter(':channelId', $channelId->getValue())
            ->andWhere($query->expr()->eq('attribute_id', ':attributeId'))
            ->setParameter(':attributeId', $attributeId->getValue())
            ->execute()
            ->rowCount();

        if ($result) {
            return true;
        }

        return false;
    }
}
