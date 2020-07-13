<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Persistence\Dbal\Repository;

use Doctrine\DBAL\Connection;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6CustomFieldRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;

/**
 */
class DbalShopware6CustomFieldRepository implements Shopware6CustomFieldRepositoryInterface
{
    private const TABLE = 'exporter.shopware6_custom_field';
    private const FIELDS = [
        'export_profile_id',
        'attribute_id',
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
     * @param AttributeId     $attributeId
     *
     * @return string|null
     */
    public function load(ExportProfileId $exportProfileId, AttributeId $attributeId): ?string
    {
        $query = $this->connection->createQueryBuilder();
        $record = $query
            ->select(self::FIELDS)
            ->from(self::TABLE, 'cf')
            ->where($query->expr()->eq('export_profile_id', ':exportProfileId'))
            ->setParameter(':exportProfileId', $exportProfileId->getValue())
            ->andWhere($query->expr()->eq('cf.attribute_id', ':attributeId'))
            ->setParameter(':attributeId', $attributeId->getValue())
            ->execute()
            ->fetch();

        if ($record) {
            return $record['shopware6_id'];
        }

        return null;
    }

    /**
     * @param ExportProfileId $exportProfileId
     * @param AttributeId     $attributeId
     * @param string          $shopwareId
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function save(ExportProfileId $exportProfileId, AttributeId $attributeId, string $shopwareId): void
    {
        if ($this->exists($exportProfileId, $attributeId)) {
            $this->update($exportProfileId, $attributeId, $shopwareId);
        } else {
            $this->insert($exportProfileId, $attributeId, $shopwareId);
        }
    }

    /**
     * @param ExportProfileId $exportProfileId
     * @param AttributeId     $attributeId
     *
     * @return bool
     */
    public function exists(ExportProfileId $exportProfileId, AttributeId $attributeId): bool
    {
        $query = $this->connection->createQueryBuilder();
        $result = $query->select(1)
            ->from(self::TABLE)
            ->where($query->expr()->eq('export_profile_id', ':exportProfileId'))
            ->setParameter(':exportProfileId', $exportProfileId->getValue())
            ->andWhere($query->expr()->eq('attribute_id', ':attributeId'))
            ->setParameter(':attributeId', $attributeId->getValue())
            ->execute()
            ->rowCount();

        if ($result) {
            return true;
        }

        return false;
    }

    /**
     * @param ExportProfileId $exportProfileId
     * @param AttributeId     $attributeId
     * @param string          $shopwareId
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function update(ExportProfileId $exportProfileId, AttributeId $attributeId, string $shopwareId): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'shopware6_id' => $shopwareId,
                'update_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            ],
            [
                'attribute_id' => $attributeId->getValue(),
                'export_profile_id' => $exportProfileId->getValue(),
            ]
        );
    }

    /**
     * @param ExportProfileId $exportProfileId
     * @param AttributeId     $attributeId
     * @param string          $shopwareId
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function insert(ExportProfileId $exportProfileId, AttributeId $attributeId, string $shopwareId): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'shopware6_id' => $shopwareId,
                'attribute_id' => $attributeId->getValue(),
                'export_profile_id' => $exportProfileId->getValue(),
                'update_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            ]
        );
    }
}
