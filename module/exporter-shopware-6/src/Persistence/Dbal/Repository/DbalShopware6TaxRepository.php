<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Persistence\Dbal\Repository;

use Doctrine\DBAL\Connection;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6TaxRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Doctrine\DBAL\DBALException;

/**
 */
class DbalShopware6TaxRepository implements Shopware6TaxRepositoryInterface
{
    private const TABLE = 'exporter.shopware6_tax';
    private const FIELDS = [
        'channel_id',
        'tax',
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
     * @param ChannelId $channelId
     * @param float     $tax
     *
     * @return string|null
     */
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
     * @param ChannelId $channelId
     * @param float     $tax
     * @param string    $shopwareId
     *
     * @throws DBALException
     */
    public function save(ChannelId $channelId, float $tax, string $shopwareId): void
    {
        if ($this->exists($channelId, $tax)) {
            $this->update($channelId, $tax, $shopwareId);
        } else {
            $this->insert($channelId, $tax, $shopwareId);
        }
    }

    /**
     * @param ChannelId $channelId
     * @param float     $tax
     *
     * @return bool
     */
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

    /**
     * @param ChannelId $channelId
     * @param float     $tax
     * @param string    $shopwareId
     *
     * @throws DBALException
     */
    private function update(ChannelId $channelId, float $tax, string $shopwareId): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'shopware6_id' => $shopwareId,
                'update_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            ],
            [
                'tax' => $tax,
                'channel_id' => $channelId->getValue(),
            ]
        );
    }

    /**
     * @param ChannelId $channelId
     * @param float     $tax
     * @param string    $shopwareId
     *
     * @throws DBALException
     */
    private function insert(ChannelId $channelId, float $tax, string $shopwareId): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'shopware6_id' => $shopwareId,
                'tax' => $tax,
                'channel_id' => $channelId->getValue(),
                'update_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            ]
        );
    }
}
