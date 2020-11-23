<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Persistence\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Types\Types;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6MultimediaRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;

class DbalShopware6MultimediaRepository implements Shopware6MultimediaRepositoryInterface
{
    private const TABLE = 'exporter.shopware6_multimedia';
    private const FIELDS = [
        'channel_id',
        'multimedia_id',
        'shopware6_id',
    ];

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function load(ChannelId $channelId, MultimediaId $multimediaId): ?string
    {
        $query = $this->connection->createQueryBuilder();
        $record = $query
            ->select(self::FIELDS)
            ->from(self::TABLE, 'm')
            ->where($query->expr()->eq('channel_id', ':channelId'))
            ->setParameter(':channelId', $channelId->getValue())
            ->andWhere($query->expr()->eq('m.multimedia_id', ':multimediaId'))
            ->setParameter(':multimediaId', $multimediaId->getValue())
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
    public function save(ChannelId $channelId, MultimediaId $multimediaId, string $shopwareId): void
    {
        if ($this->exists($channelId, $multimediaId)) {
            $this->update($channelId, $multimediaId, $shopwareId);
        } else {
            $this->insert($channelId, $multimediaId, $shopwareId);
        }
    }

    public function exists(ChannelId $channelId, MultimediaId $multimediaId): bool
    {
        $query = $this->connection->createQueryBuilder();
        $result = $query->select(1)
            ->from(self::TABLE)
            ->where($query->expr()->eq('channel_id', ':channelId'))
            ->setParameter(':channelId', $channelId->getValue())
            ->andWhere($query->expr()->eq('multimedia_id', ':multimediaId'))
            ->setParameter(':multimediaId', $multimediaId->getValue())
            ->execute()
            ->rowCount();

        if ($result) {
            return true;
        }

        return false;
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Exception\InvalidArgumentException
     */
    public function delete(ChannelId $channelId, MultimediaId $multimediaId): void
    {
        $this->connection->delete(
            self::TABLE,
            [
                'multimedia_id' => $multimediaId->getValue(),
                'channel_id' => $channelId->getValue(),
            ]
        );
    }

    /**
     * @throws DBALException
     */
    private function update(ChannelId $channelId, MultimediaId $multimediaId, string $shopwareId): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'shopware6_id' => $shopwareId,
                'update_at' => new \DateTimeImmutable(),
            ],
            [
                'multimedia_id' => $multimediaId->getValue(),
                'channel_id' => $channelId->getValue(),
            ],
            [
                'update_at' => Types::DATETIMETZ_MUTABLE,
            ],
        );
    }

    /**
     * @throws DBALException
     */
    private function insert(ChannelId $channelId, MultimediaId $multimediaId, string $shopwareId): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'shopware6_id' => $shopwareId,
                'multimedia_id' => $multimediaId->getValue(),
                'channel_id' => $channelId->getValue(),
                'update_at' => new \DateTimeImmutable(),
            ],
            [
                'update_at' => Types::DATETIMETZ_MUTABLE,
            ],
        );
    }
}
