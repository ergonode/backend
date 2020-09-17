<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Persistence\Dbal\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Types\Types;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6PropertyGroupOptionsRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\AggregateId;

/**
 */
class DbalShopware6PropertyGroupOptionsRepository implements Shopware6PropertyGroupOptionsRepositoryInterface
{
    private const TABLE = 'exporter.shopware6_property_group_options';
    private const FIELDS = [
        'channel_id',
        'attribute_id',
        'shopware6_id',
        'option_id',
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
     * @param AggregateId $optionId
     *
     * @return string|null
     */
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
     * @param ChannelId   $channelId
     * @param AttributeId $attributeId
     * @param AggregateId $optionId
     * @param string      $shopwareId
     *
     * @throws DBALException
     */
    public function save(
        ChannelId $channelId,
        AttributeId $attributeId,
        AggregateId $optionId,
        string $shopwareId
    ): void {
        if ($this->exists($channelId, $attributeId, $optionId)) {
            $this->update($channelId, $attributeId, $optionId, $shopwareId);
        } else {
            $this->insert($channelId, $attributeId, $optionId, $shopwareId);
        }
    }

    /**
     * @param ChannelId   $channelId
     * @param AttributeId $attributeId
     * @param AggregateId $optionId
     *
     * @return bool
     */
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

    /**
     * @param ChannelId   $channelId
     * @param AttributeId $attributeId
     * @param AggregateId $optionId
     * @param string      $shopwareId
     *
     * @throws DBALException
     */
    private function update(
        ChannelId $channelId,
        AttributeId $attributeId,
        AggregateId $optionId,
        string $shopwareId
    ): void {
        $this->connection->update(
            self::TABLE,
            [
                'shopware6_id' => $shopwareId,
                'update_at' => new \DateTimeImmutable(),
            ],
            [
                'attribute_id' => $attributeId->getValue(),
                'channel_id' => $channelId->getValue(),
                'option_id' => $optionId->getValue(),
            ],
            [
                'update_at' => Types::DATETIMETZ_MUTABLE,
            ],
        );
    }

    /**
     * @param ChannelId   $channelId
     * @param AttributeId $attributeId
     * @param AggregateId $optionId
     * @param string      $shopwareId
     *
     * @throws DBALException
     */
    private function insert(
        ChannelId $channelId,
        AttributeId $attributeId,
        AggregateId $optionId,
        string $shopwareId
    ): void {
        $this->connection->insert(
            self::TABLE,
            [
                'shopware6_id' => $shopwareId,
                'attribute_id' => $attributeId->getValue(),
                'option_id' => $optionId->getValue(),
                'channel_id' => $channelId->getValue(),
                'update_at' => new \DateTimeImmutable(),
            ],
            [
                'update_at' => Types::DATETIMETZ_MUTABLE,
            ],
        );
    }
}
