<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Persistence\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Exporter\Domain\Entity\Catalog\ExportCategory;
use Ergonode\ExporterShopware6\Domain\Entity\Catalog\Shopware6Category;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6ExportApiProfile;
use JMS\Serializer\SerializerInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class DbalShopwer6CategoryRepository
{
    private const TABLE = 'exporter.shopware6_category';
    private const TABLE_CATEGORY = 'exporter.category';
    private const FIELDS = [
        'export_profile_id',
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
     * @param Uuid $exportProfileId
     * @param Uuid $categoryId
     *
     * @return Shopware6Category|null
     */
    public function load(Uuid $exportProfileId, Uuid $categoryId): ?Shopware6Category
    {
        $query = $this->connection->createQueryBuilder();
        $record = $query
            ->select('*')
            ->from(self::TABLE, 'cs')
            ->leftJoin('cs', self::TABLE_CATEGORY, 'c', 'c.id = cs.category_id')
            ->where($query->expr()->eq('export_profile_id', ':exportProfileId'))
            ->setParameter(':exportProfileId', $exportProfileId->toString())
            ->andWhere($query->expr()->eq('c.id', ':categoryId'))
            ->setParameter(':categoryId', $categoryId->toString())
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
     * @param Uuid   $exportProfileId
     * @param Uuid   $categoryId
     * @param string $shopwareId
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function save(Uuid $exportProfileId, Uuid $categoryId, string $shopwareId): void
    {
        if ($this->exists($exportProfileId, $categoryId)) {
            $this->update($exportProfileId, $categoryId, $shopwareId);
        } else {
            $this->insert($exportProfileId, $categoryId, $shopwareId);
        }
    }

    /**
     * @param Uuid $exportProfileId
     * @param Uuid $categoryId
     *
     * @return bool
     */
    public function exists(
        Uuid $exportProfileId,
        Uuid $categoryId
    ): bool {
        $query = $this->connection->createQueryBuilder();
        $result = $query->select(1)
            ->from(self::TABLE)
            ->where($query->expr()->eq('export_profile_id', ':exportProfileId'))
            ->setParameter(':exportProfileId', $exportProfileId->toString())
            ->andWhere($query->expr()->eq('category_id', ':categoryId'))
            ->setParameter(':categoryId', $categoryId->toString())
            ->execute()
            ->rowCount();


        if ($result) {
            return true;
        }

        return false;
    }

    /**
     * @param Uuid   $exportProfileId
     * @param Uuid   $categoryId
     * @param string $shopwareId
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function update(Uuid $exportProfileId, Uuid $categoryId, string $shopwareId): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'shopware6_id' => $shopwareId,
            ],
            [
                'category_id' => $categoryId->toString(),
                'export_profile_id' => $exportProfileId->toString(),
            ]
        );
    }

    /**
     * @param Uuid   $exportProfileId
     * @param Uuid   $categoryId
     * @param string $shopwareId
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function insert(Uuid $exportProfileId, Uuid $categoryId, string $shopwareId): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'shopware6_id' => $shopwareId,
                'category_id' => $categoryId->toString(),
                'export_profile_id' => $exportProfileId->toString(),
            ]
        );
    }
}
