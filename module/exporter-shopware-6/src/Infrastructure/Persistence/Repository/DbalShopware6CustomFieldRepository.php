<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Persistence\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6CustomFieldRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

class DbalShopware6CustomFieldRepository implements Shopware6CustomFieldRepositoryInterface
{
    private const TABLE = 'exporter.shopware6_custom_field';
    private const FIELDS = [
        'channel_id',
        'attribute_id',
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
     * @param ChannelId   $channel
     * @param AttributeId $attributeId
     *
     * @return string|null
     */
    public function load(ChannelId $channel, AttributeId $attributeId): ?string
    {
        $query = $this->connection->createQueryBuilder();
        $record = $query
            ->select(self::FIELDS)
            ->from(self::TABLE, 'cf')
            ->where($query->expr()->eq('channel_id', ':channelId'))
            ->setParameter(':channelId', $channel->getValue())
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
     * @param ChannelId   $channel
     * @param AttributeId $attributeId
     * @param string      $shopwareId
     * @param string      $type
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function save(ChannelId $channel, AttributeId $attributeId, string $shopwareId, string $type): void
    {
        if ($this->exists($channel, $attributeId)) {
            $this->update($channel, $attributeId, $shopwareId);
        } else {
            $this->insert($channel, $attributeId, $shopwareId, $type);
        }
    }

    /**
     * @param ChannelId   $channel
     * @param AttributeId $attributeId
     *
     * @return bool
     */
    public function exists(ChannelId $channel, AttributeId $attributeId): bool
    {
        $query = $this->connection->createQueryBuilder();
        $result = $query->select(1)
            ->from(self::TABLE)
            ->where($query->expr()->eq('channel_id', ':channelId'))
            ->setParameter(':channelId', $channel->getValue())
            ->andWhere($query->expr()->eq('attribute_id', ':attributeId'))
            ->setParameter(':attributeId', $attributeId->getValue())
            ->execute()
            ->rowCount();

        if ($result) {
            return true;
        }

        return false;
    }

    /**
     * @param ChannelId   $channel
     * @param AttributeId $attributeId
     * @param string      $shopwareId
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function update(ChannelId $channel, AttributeId $attributeId, string $shopwareId): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'shopware6_id' => $shopwareId,
                'update_at' => new \DateTimeImmutable(),
            ],
            [
                'attribute_id' => $attributeId->getValue(),
                'channel_id' => $channel->getValue(),
            ],
            [
                'update_at' => Types::DATETIMETZ_MUTABLE,
            ],
        );
    }

    /**
     * @param ChannelId   $channel
     * @param AttributeId $attributeId
     * @param string      $shopwareId
     * @param string      $type
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function insert(ChannelId $channel, AttributeId $attributeId, string $shopwareId, string $type): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'shopware6_id' => $shopwareId,
                'attribute_id' => $attributeId->getValue(),
                'type' => $type,
                'channel_id' => $channel->getValue(),
                'update_at' => new \DateTimeImmutable(),
            ],
            [
                'update_at' => Types::DATETIMETZ_MUTABLE,
            ]
        );
    }
}
