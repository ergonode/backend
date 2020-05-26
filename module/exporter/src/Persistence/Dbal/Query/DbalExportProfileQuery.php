<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Ergonode\Exporter\Domain\Query\ExportProfileQueryInterface;

/**
 */
class DbalExportProfileQuery implements ExportProfileQueryInterface
{
    private const TABLE = 'exporter.export_profile';

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
     * @return array
     */
    public function getAllExportProfileIds(): array
    {
        $result = $this->connection->createQueryBuilder()
            ->select('id')
            ->from(self::TABLE)
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);

        if (false !== $result) {
            return $result;
        }

        return [];
    }
}
