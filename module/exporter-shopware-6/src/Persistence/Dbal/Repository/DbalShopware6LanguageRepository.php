<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Persistence\Dbal\Repository;

use Doctrine\DBAL\Connection;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6LanguageRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;

/**
 */
class DbalShopware6LanguageRepository implements Shopware6LanguageRepositoryInterface
{
    private const TABLE = 'exporter.shopware6_language';
    private const FIELDS = [
        'export_profile_id',
        'name',
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
     * @param ExportProfileId $exportProfileId
     * @param string          $name
     *
     * @return string|null
     */
    public function load(ExportProfileId $exportProfileId, string $name): ?string
    {
        $query = $this->connection->createQueryBuilder();
        $record = $query
            ->select(self::FIELDS)
            ->from(self::TABLE, 'c')
            ->where($query->expr()->eq('export_profile_id', ':exportProfileId'))
            ->setParameter(':exportProfileId', $exportProfileId->getValue())
            ->andWhere($query->expr()->eq('c.name', ':name'))
            ->setParameter(':name', $name)
            ->execute()
            ->fetch();

        if ($record) {
            return $record['shopware6_id'];
        }

        return null;
    }

    /**
     * @param ExportProfileId $exportProfileId
     * @param string          $name
     * @param string          $shopwareId
     */
    public function save(ExportProfileId $exportProfileId, string $name, string $shopwareId): void
    {
        if ($this->exists($exportProfileId, $name)) {
            $this->update($exportProfileId, $name, $shopwareId);
        } else {
            $this->insert($exportProfileId, $name, $shopwareId);
        }
    }

    /**
     * @param ExportProfileId $exportProfileId
     * @param string          $name
     *
     * @return bool
     */
    public function exists(ExportProfileId $exportProfileId, string $name): bool
    {
        $query = $this->connection->createQueryBuilder();
        $result = $query->select(1)
            ->from(self::TABLE)
            ->where($query->expr()->eq('export_profile_id', ':exportProfileId'))
            ->setParameter(':exportProfileId', $exportProfileId->getValue())
            ->andWhere($query->expr()->eq('name', ':name'))
            ->setParameter(':name', $name)
            ->execute()
            ->rowCount();

        if ($result) {
            return true;
        }

        return false;
    }

    /**
     * @param ExportProfileId $exportProfileId
     * @param string          $name
     * @param string          $shopwareId
     */
    private function update(ExportProfileId $exportProfileId, string $name, string $shopwareId): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'shopware6_id' => $shopwareId,
                'update_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            ],
            [
                'iso' => $name,
                'export_profile_id' => $exportProfileId->getValue(),
            ]
        );
    }

    /**
     * @param ExportProfileId $exportProfileId
     * @param string          $name
     * @param string          $shopwareId
     */
    private function insert(ExportProfileId $exportProfileId, string $name, string $shopwareId): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'shopware6_id' => $shopwareId,
                'name' => $name,
                'export_profile_id' => $exportProfileId->getValue(),
                'update_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            ]
        );
    }
}
