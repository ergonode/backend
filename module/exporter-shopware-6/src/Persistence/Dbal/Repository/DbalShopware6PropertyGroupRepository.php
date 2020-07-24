<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Persistence\Dbal\Repository;

use Doctrine\DBAL\Connection;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6PropertyGroupRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Doctrine\DBAL\DBALException;

/**
 */
class DbalShopware6PropertyGroupRepository implements Shopware6PropertyGroupRepositoryInterface
{
    private const TABLE = 'exporter.shopware6_property_group';
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
     * @param ChannelId   $channelId
     * @param AttributeId $attributeId
     *
     * @return string|null
     */
    public function load(ChannelId $channelId, AttributeId $attributeId): ?string
    {
        $query = $this->connection->createQueryBuilder();
        $record = $query
            ->select(self::FIELDS)
            ->from(self::TABLE, 'pg')
            ->where($query->expr()->eq('channel_id', ':channelId'))
            ->setParameter(':channelId', $channelId->getValue())
            ->andWhere($query->expr()->eq('pg.attribute_id', ':attributeId'))
            ->setParameter(':attributeId', $attributeId->getValue())
            ->execute()
            ->fetch();

        if ($record) {
            return $record['shopware6_id'];
        }

        return null;
    }

    /**
     * @param ChannelId   $channelId
     * @param AttributeId $attributeId
     * @param string      $shopwareId
     *
     * @throws DBALException
     */
    public function save(ChannelId $channelId, AttributeId $attributeId, string $shopwareId): void
    {
        if ($this->exists($channelId, $attributeId)) {
            $this->update($channelId, $attributeId, $shopwareId);
        } else {
            $this->insert($channelId, $attributeId, $shopwareId);
        }
    }

    /**
     * @param ChannelId   $channelId
     * @param AttributeId $attributeId
     *
     * @return bool
     */
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

    /**
     * @param ChannelId   $channelId
     * @param AttributeId $attributeId
     * @param string      $shopwareId
     *
     * @throws DBALException
     */
    private function update(ChannelId $channelId, AttributeId $attributeId, string $shopwareId): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'shopware6_id' => $shopwareId,
                'update_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            ],
            [
                'attribute_id' => $attributeId->getValue(),
                'channel_id' => $channelId->getValue(),
            ]
        );
    }

    /**
     * @param ChannelId   $channelId
     * @param AttributeId $attributeId
     * @param string      $shopwareId
     *
     * @throws DBALException
     */
    private function insert(ChannelId $channelId, AttributeId $attributeId, string $shopwareId): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'shopware6_id' => $shopwareId,
                'attribute_id' => $attributeId->getValue(),
                'channel_id' => $channelId->getValue(),
                'update_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            ]
        );
    }
}
