<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Persistence\Dbal\Repository;

use Doctrine\DBAL\Connection;
use Ergonode\Exporter\Domain\Entity\Catalog\ExportCategory;
use Ergonode\ExporterShopware6\Domain\Entity\Catalog\Shopware6Category;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6CategoryRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use JMS\Serializer\SerializerInterface;

/**
 */
class DbalShopware6CategoryRepository implements Shopware6CategoryRepositoryInterface
{
    private const TABLE = 'exporter.shopware6_category';
    private const TABLE_CATEGORY = 'exporter.category';
    private const FIELDS = [
        'channel_id',
        'c.category_id',
        'cs.shopware6_id',
    ];

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @param Connection          $connection
     * @param SerializerInterface $serializer
     */
    public function __construct(Connection $connection, SerializerInterface $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    /**
     * @param ChannelId  $channelId
     * @param CategoryId $categoryId
     *
     * @return Shopware6Category|null
     */
    public function load(ChannelId $channelId, CategoryId $categoryId): ?Shopware6Category
    {
        $query = $this->connection->createQueryBuilder();
        $record = $query
            ->select('*')
            ->from(self::TABLE, 'cs')
            ->leftJoin('cs', self::TABLE_CATEGORY, 'c', 'c.id = cs.category_id')
            ->where($query->expr()->eq('channel_id', ':channelId'))
            ->setParameter(':channelId', $channelId->getValue())
            ->andWhere($query->expr()->eq('c.id', ':categoryId'))
            ->setParameter(':categoryId', $categoryId->getValue())
            ->execute()
            ->fetch();

        if ($record) {
            return new Shopware6Category(
                $record['shopware6_id'],
                $this->serializer->deserialize($record['data'], ExportCategory::class, 'json')
            );
        }

        return null;
    }

    /**
     * @param ChannelId  $channelId
     * @param CategoryId $categoryId
     * @param string     $shopwareId
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function save(ChannelId $channelId, CategoryId $categoryId, string $shopwareId): void
    {
        if ($this->exists($channelId, $categoryId)) {
            $this->update($channelId, $categoryId, $shopwareId);
        } else {
            $this->insert($channelId, $categoryId, $shopwareId);
        }
    }

    /**
     * @param ChannelId  $channelId
     * @param CategoryId $categoryId
     *
     * @return bool
     */
    public function exists(
        ChannelId $channelId,
        CategoryId $categoryId
    ): bool {
        $query = $this->connection->createQueryBuilder();
        $result = $query->select(1)
            ->from(self::TABLE)
            ->where($query->expr()->eq('channel_id', ':channelId'))
            ->setParameter(':channelId', $channelId->getValue())
            ->andWhere($query->expr()->eq('category_id', ':categoryId'))
            ->setParameter(':categoryId', $categoryId->getValue())
            ->execute()
            ->rowCount();


        if ($result) {
            return true;
        }

        return false;
    }

    /**
     * @param ChannelId  $channelId
     * @param CategoryId $categoryId
     * @param string     $shopwareId
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function update(ChannelId $channelId, CategoryId $categoryId, string $shopwareId): void
    {
        $now = new \DateTimeImmutable();
        $this->connection->update(
            self::TABLE,
            [
                'shopware6_id' => $shopwareId,
                'update_at' => $now->format('Y-m-d H:i:s'),
            ],
            [
                'category_id' => $categoryId->getValue(),
                'channel_id' => $channelId->getValue(),
            ]
        );
    }

    /**
     * @param ChannelId  $channelId
     * @param CategoryId $categoryId
     * @param string     $shopwareId
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function insert(ChannelId $channelId, CategoryId $categoryId, string $shopwareId): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'shopware6_id' => $shopwareId,
                'category_id' => $categoryId->getValue(),
                'channel_id' => $channelId->getValue(),
                'update_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            ]
        );
    }
}
