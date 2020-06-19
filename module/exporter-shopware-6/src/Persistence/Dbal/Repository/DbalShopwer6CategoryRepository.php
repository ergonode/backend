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
use Ergonode\ExporterShopware6\Domain\Repository\Shopwer6CategoryRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;
use JMS\Serializer\SerializerInterface;

/**
 */
class DbalShopwer6CategoryRepository implements Shopwer6CategoryRepositoryInterface
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
     * @param ExportProfileId $exportProfileId
     * @param CategoryId      $categoryId
     *
     * @return Shopware6Category|null
     */
    public function load(ExportProfileId $exportProfileId, CategoryId $categoryId): ?Shopware6Category
    {
        $query = $this->connection->createQueryBuilder();
        $record = $query
            ->select('*')
            ->from(self::TABLE, 'cs')
            ->leftJoin('cs', self::TABLE_CATEGORY, 'c', 'c.id = cs.category_id')
            ->where($query->expr()->eq('export_profile_id', ':exportProfileId'))
            ->setParameter(':exportProfileId', $exportProfileId->getValue())
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
     * @param ExportProfileId $exportProfileId
     * @param CategoryId      $categoryId
     * @param string          $shopwareId
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function save(ExportProfileId $exportProfileId, CategoryId $categoryId, string $shopwareId): void
    {
        if ($this->exists($exportProfileId, $categoryId)) {
            $this->update($exportProfileId, $categoryId, $shopwareId);
        } else {
            $this->insert($exportProfileId, $categoryId, $shopwareId);
        }
    }

    /**
     * @param ExportProfileId $exportProfileId
     * @param CategoryId      $categoryId
     *
     * @return bool
     */
    public function exists(
        ExportProfileId $exportProfileId,
        CategoryId $categoryId
    ): bool {
        $query = $this->connection->createQueryBuilder();
        $result = $query->select(1)
            ->from(self::TABLE)
            ->where($query->expr()->eq('export_profile_id', ':exportProfileId'))
            ->setParameter(':exportProfileId', $exportProfileId->getValue())
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
     * @param ExportProfileId $exportProfileId
     * @param CategoryId      $categoryId
     * @param string          $shopwareId
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function update(ExportProfileId $exportProfileId, CategoryId $categoryId, string $shopwareId): void
    {
        $now = new \DateTimeImmutable();
        $this->connection->update(
            self::TABLE,
            [
                'category_id' => $categoryId->getValue(),
                'export_profile_id' => $exportProfileId->getValue(),
                'update_at' => $now->format('Y-m-d H:i:s'),
            ],
            [
                'shopware6_id' => $shopwareId,
            ]
        );
    }

    /**
     * @param ExportProfileId $exportProfileId
     * @param CategoryId      $categoryId
     * @param string          $shopwareId
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function insert(ExportProfileId $exportProfileId, CategoryId $categoryId, string $shopwareId): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'shopware6_id' => $shopwareId,
                'category_id' => $categoryId->getValue(),
                'export_profile_id' => $exportProfileId->getValue(),
                'update_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            ]
        );
    }
}
