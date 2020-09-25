<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Ergonode\Exporter\Domain\Query\ExportProductQueryInterface;

/**
 */
class DbalExportProductQuery implements ExportProductQueryInterface
{
    private const PRODUCT_TABLE = 'exporter.product';

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
     * @return array|null
     */
    public function getAllIds(): ?array
    {
        $result = $this->connection->createQueryBuilder()
            ->select('id')
            ->from(self::PRODUCT_TABLE)
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);

        if (false !== $result) {
            return $result;
        }

        return null;
    }
}
