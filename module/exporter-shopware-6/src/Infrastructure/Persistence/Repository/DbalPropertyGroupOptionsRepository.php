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
use Ergonode\ExporterShopware6\Domain\Repository\PropertyGroupOptionsRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\AggregateId;

class DbalPropertyGroupOptionsRepository implements PropertyGroupOptionsRepositoryInterface
{
    private const TABLE = 'exporter.shopware6_property_group_options';
    private const FIELDS = [
        'channel_id',
        'attribute_id',
        'shopware6_id',
        'option_id',
    ];
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function load(ChannelId $channelId, AttributeId $attributeId, AggregateId $optionId): ?string
    {
        $query = $this->connection->createQueryBuilder();
        $record = $query
            ->select(self::FIELDS)
            ->from(self::TABLE, 'pgo')
            ->where($query->expr()->eq('pgo.channel_id', ':channelId'))
            ->setParameter(':channelId', $channelId->getValue())
            ->andWhere($query->expr()->eq('pgo.attribute_id', ':attributeId'))
            ->setParameter(':attributeId', $attributeId->getValue())
            ->andWhere($query->expr()->eq('pgo.option_id', ':optionId'))
            ->setParameter(':optionId', $optionId->getValue())
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
        AttributeId $attributeId,
        AggregateId $optionId,
        string $shopwareId
    ): void {

        $sql = 'INSERT INTO '.self::TABLE.' (channel_id, attribute_id, option_id, shopware6_id, update_at) 
        VALUES (:channelId, :attributeId, :optionId, :shopware6Id, :updatedAt)
            ON CONFLICT ON CONSTRAINT shopware6_property_group_options_pkey
                DO UPDATE SET shopware6_id = :shopware6Id, update_at = :updatedAt
        ';

        $this->connection->executeQuery(
            $sql,
            [
                'channelId' => $channelId->getValue(),
                'attributeId' => $attributeId->getValue(),
                'optionId' => $optionId->getValue(),
                'shopware6Id' => $shopwareId,
                'updatedAt' => new \DateTimeImmutable(),
            ],
            [
                'updatedAt' => Types::DATETIMETZ_MUTABLE,
            ]
        );
    }

    public function exists(ChannelId $channelId, AttributeId $attributeId, AggregateId $optionId): bool
    {

        $query = $this->connection->createQueryBuilder();
        $result = $query->select(1)
            ->from(self::TABLE, 'pgo')
            ->where($query->expr()->eq('pgo.channel_id', ':channelId'))
            ->setParameter(':channelId', $channelId->getValue())
            ->andWhere($query->expr()->eq('pgo.attribute_id', ':attributeId'))
            ->setParameter(':attributeId', $attributeId->getValue())
            ->andWhere($query->expr()->eq('pgo.option_id', ':optionId'))
            ->setParameter(':optionId', $optionId->getValue())
            ->execute()
            ->rowCount();

        if ($result) {
            return true;
        }

        return false;
    }
}
