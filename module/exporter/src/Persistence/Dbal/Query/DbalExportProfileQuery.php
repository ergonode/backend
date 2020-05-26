<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Ergonode\Exporter\Domain\Query\ExportProfileQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;
use Doctrine\DBAL\Query\QueryBuilder;

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
     * @param Language $language
     *
     * @return DataSetInterface
     */
    public function getDataSet(Language $language): DataSetInterface
    {
        $query = $this->getQuery();

        $result = $this->connection->createQueryBuilder();
        $result->select('*');
        $result->from(sprintf('(%s)', $query->getSQL()), 't');

        return new DbalDataSet($result);
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

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('*')
            ->from(self::TABLE);
    }
}
