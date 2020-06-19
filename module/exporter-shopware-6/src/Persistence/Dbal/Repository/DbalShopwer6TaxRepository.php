<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Persistence\Dbal\Repository;

use Doctrine\DBAL\Connection;
use Ergonode\Core\Application\Exception\NotImplementedException;
use Ergonode\ExporterShopware6\Domain\Repository\Shopwer6TaxRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;
use Ramsey\Uuid\Uuid;

/**
 */
class DbalShopwer6TaxRepository implements Shopwer6TaxRepositoryInterface
{
    private const TABLE = 'exporter.shopware6_tax';
    private const FIELDS = [
        'export_profile_id',
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
     * @param ExportProfileId $exportProfileId
     * @param float           $tax
     *
     * @return string|null
     */
    public function load(ExportProfileId $exportProfileId, float $tax): ?string
    {
        $query = $this->connection->createQueryBuilder();
        $record = $query
            ->select(self::FIELDS)
            ->from(self::TABLE, 't')
            ->where($query->expr()->eq('export_profile_id', ':exportProfileId'))
            ->setParameter(':exportProfileId', $exportProfileId->getValue())
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
     * @param ExportProfileId $exportProfileId
     * @param float           $tax
     * @param string          $shopwareId
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function save(ExportProfileId $exportProfileId, float $tax, string $shopwareId): void
    {
        if ($this->exists($exportProfileId, $tax)) {
            $this->update($exportProfileId, $tax, $shopwareId);
        } else {
            $this->insert($exportProfileId, $tax, $shopwareId);
        }
    }

    /**
     * @param ExportProfileId $exportProfileId
     * @param float           $tax
     *
     * @return bool
     */
    public function exists(
        ExportProfileId $exportProfileId,
        float $tax
    ): bool {
        $query = $this->connection->createQueryBuilder();
        $result = $query->select(1)
            ->from(self::TABLE)
            ->where($query->expr()->eq('export_profile_id', ':exportProfileId'))
            ->setParameter(':exportProfileId', $exportProfileId->getValue())
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
     * @param ExportProfileId $exportProfileId
     * @param float           $tax
     * @param string          $shopwareId
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function update(ExportProfileId $exportProfileId, float $tax, string $shopwareId): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'tax' => $tax,
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
     * @param float           $tax
     * @param string          $shopwareId
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function insert(ExportProfileId $exportProfileId, float $tax, string $shopwareId): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'shopware6_id' => $shopwareId,
                'tax' => $tax,
                'export_profile_id' => $exportProfileId->getValue(),
                'update_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            ]
        );
    }
}
