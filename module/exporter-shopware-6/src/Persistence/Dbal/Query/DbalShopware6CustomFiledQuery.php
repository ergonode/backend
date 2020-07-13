<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Ergonode\ExporterShopware6\Domain\Query\Shopware6CustomFieldQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;

/**
 */
class DbalShopware6CustomFiledQuery implements Shopware6CustomFieldQueryInterface
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
     * @param string          $shopwareId
     *
     * @return AttributeId|null
     */
    public function loadByShopwareId(ExportProfileId $exportProfileId, string $shopwareId): ?AttributeId
    {
        $query = $this->connection->createQueryBuilder();
        $record = $query
            ->select(self::FIELDS)
            ->from(self::TABLE, 'pg')
            ->where($query->expr()->eq('export_profile_id', ':exportProfileId'))
            ->setParameter(':exportProfileId', $exportProfileId->getValue())
            ->andWhere($query->expr()->eq('shopware6_id', ':shopware6Id'))
            ->setParameter(':shopware6Id', $shopwareId)
            ->execute()
            ->fetch();

        if ($record) {
            return new AttributeId($record['attribute_id']);
        }

        return null;
    }

    /**
     * @param ExportProfileId    $exportProfileId
     * @param \DateTimeImmutable $dateTime
     */
    public function clearBefore(ExportProfileId $exportProfileId, \DateTimeImmutable $dateTime): void
    {
        $query = $this->connection->createQueryBuilder();
        $query->delete(self::TABLE, 'pg')
            ->where($query->expr()->eq('export_profile_id', ':exportProfileId'))
            ->setParameter(':exportProfileId', $exportProfileId->getValue())
            ->andWhere($query->expr()->lt('pg.update_at', ':updateAt'))
            ->setParameter(':updateAt', $dateTime->format('Y-m-d H:i:s'))
            ->execute();
    }
}
