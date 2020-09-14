<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Persistence\Dbal\Repository;

use Doctrine\DBAL\Connection;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6CurrencyRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Doctrine\DBAL\DBALException;

/**
 */
class DbalShopware6CurrencyRepository implements Shopware6CurrencyRepositoryInterface
{

    private const TABLE = 'exporter.shopware6_currency';
    private const FIELDS = [
        'channel_id',
        'iso',
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
     * @param ChannelId $channel
     * @param string    $iso
     *
     * @return string|null
     */
    public function load(ChannelId $channel, string $iso): ?string
    {

        $query = $this->connection->createQueryBuilder();
        $record = $query
            ->select(self::FIELDS)
            ->from(self::TABLE, 'c')
            ->where($query->expr()->eq('channel_id', ':channelId'))
            ->setParameter(':channelId', $channel->getValue())
            ->andWhere($query->expr()->eq('c.iso', ':iso'))
            ->setParameter(':iso', $iso)
            ->execute()
            ->fetch();

        if ($record) {
            return $record['shopware6_id'];
        }

        return null;
    }

    /**
     * @param ChannelId $channel
     * @param string    $iso
     * @param string    $shopwareId
     *
     * @throws DBALException
     */
    public function save(ChannelId $channel, string $iso, string $shopwareId): void
    {
        if ($this->exists($channel, $iso)) {
            $this->update($channel, $iso, $shopwareId);
        } else {
            $this->insert($channel, $iso, $shopwareId);
        }
    }

    /**
     * @param ChannelId $channel
     * @param string    $iso
     *
     * @return bool
     */
    public function exists(
        ChannelId $channel,
        string $iso
    ): bool {
        $query = $this->connection->createQueryBuilder();
        $result = $query->select(1)
            ->from(self::TABLE)
            ->where($query->expr()->eq('channel_id', ':channelId'))
            ->setParameter(':channelId', $channel->getValue())
            ->andWhere($query->expr()->eq('iso', ':iso'))
            ->setParameter(':iso', $iso)
            ->execute()
            ->rowCount();

        if ($result) {
            return true;
        }

        return false;
    }

    /**
     * @param ChannelId $channel
     * @param string    $iso
     * @param string    $shopwareId
     *
     * @throws DBALException
     */
    private function update(ChannelId $channel, string $iso, string $shopwareId): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'shopware6_id' => $shopwareId,
                'update_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            ],
            [
                'iso' => $iso,
                'channel_id' => $channel->getValue(),
            ]
        );
    }

    /**
     * @param ChannelId $channel
     * @param string    $iso
     * @param string    $shopwareId
     *
     * @throws DBALException
     */
    private function insert(ChannelId $channel, string $iso, string $shopwareId): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'shopware6_id' => $shopwareId,
                'iso' => $iso,
                'channel_id' => $channel->getValue(),
                'update_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            ]
        );
    }
}
