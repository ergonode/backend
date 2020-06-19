<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Persistence\Dbal\Repository;

use Doctrine\DBAL\Connection;
use Ergonode\ExporterShopware6\Domain\Repository\Shopwer6CurrencyRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;

/**
 */
class DbalShopwer6CurrencyRepository implements Shopwer6CurrencyRepositoryInterface
{

    private const TABLE = 'exporter.shopware6_currency';
    private const FIELDS = [
        'export_profile_id',
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
     * @param ExportProfileId $exportProfileId
     * @param string          $iso
     *
     * @return string|null
     */
    public function load(ExportProfileId $exportProfileId, string $iso): ?string
    {

        $query = $this->connection->createQueryBuilder();
        $record = $query
            ->select(self::FIELDS)
            ->from(self::TABLE, 'c')
            ->where($query->expr()->eq('export_profile_id', ':exportProfileId'))
            ->setParameter(':exportProfileId', $exportProfileId->getValue())
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
     * @param ExportProfileId $exportProfileId
     * @param string          $iso
     * @param string          $shopwareId
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function save(ExportProfileId $exportProfileId, string $iso, string $shopwareId): void
    {
        if ($this->exists($exportProfileId, $iso)) {
            $this->update($exportProfileId, $iso, $shopwareId);
        } else {
            $this->insert($exportProfileId, $iso, $shopwareId);
        }
    }

    /**
     * @param ExportProfileId $exportProfileId
     * @param string          $iso
     *
     * @return bool
     */
    public function exists(
        ExportProfileId $exportProfileId,
        string $iso
    ): bool {
        $query = $this->connection->createQueryBuilder();
        $result = $query->select(1)
            ->from(self::TABLE)
            ->where($query->expr()->eq('export_profile_id', ':exportProfileId'))
            ->setParameter(':exportProfileId', $exportProfileId->getValue())
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
     * @param ExportProfileId $exportProfileId
     * @param string          $iso
     * @param string          $shopwareId
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function update(ExportProfileId $exportProfileId, string $iso, string $shopwareId): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'iso' => $iso,
                'export_profile_id' => $exportProfileId->getValue(),
                'update_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            ],
            [
                'shopware6_id' => $shopwareId,
            ]
        );
    }

    /**
     * @param ExportProfileId $exportProfileId
     * @param string          $iso
     * @param string          $shopwareId
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function insert(ExportProfileId $exportProfileId, string $iso, string $shopwareId): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'shopware6_id' => $shopwareId,
                'iso' => $iso,
                'export_profile_id' => $exportProfileId->getValue(),
                'update_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            ]
        );
    }
}
