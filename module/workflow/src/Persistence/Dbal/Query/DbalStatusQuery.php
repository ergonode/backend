<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;
use Ergonode\Workflow\Domain\Query\StatusQueryInterface;

/**
 */
class DbalStatusQuery implements StatusQueryInterface
{
    private const STATUS_TABLE = 'public.status';

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
        $query = $this->getQuery($language);
        $query->addSelect(
            '(SELECT CASE WHEN count(*) > 0 THEN true ELSE false END FROM workflow w WHERE '.
            ' w.default_status = a.id AND w.code =\'default\')::BOOLEAN AS is_default '
        );

        $result = $this->connection->createQueryBuilder();
        $result->select('*');
        $result->from(sprintf('(%s)', $query->getSQL()), 't');

        return new DbalDataSet($result);
    }

    /**
     * @param Language $language
     *
     * @return array
     */
    public function getDictionary(Language $language): array
    {
        return $this->getQuery($language)
            ->select('id, code')
            ->orderBy('name', 'desc')
            ->execute()
            ->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    /**
     * @param Language $language
     *
     * @return array
     */
    public function getAllStatuses(language $language): array
    {
        $qb = $this->connection->createQueryBuilder();

        $records = $qb->select(sprintf('code, color, name->>\'%s\' as name', $language->getCode()))
            ->from(self::STATUS_TABLE, 'a')
            ->execute()
            ->fetchAll();

        $result = [];
        foreach ($records as $record) {
            $result[$record['code']]['color'] = $record['color'];
            $result[$record['code']]['name'] = $record['name'];
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getAllCodes(): array
    {
        $qb = $this->connection->createQueryBuilder();

        return $qb->select('code')
            ->from(self::STATUS_TABLE, 'a')
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * @param Language $language
     *
     * @return QueryBuilder
     */
    private function getQuery(Language $language): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(sprintf(
                'id, code, code AS status, color, name->>\'%s\' as name, description->>\'%s\' as description',
                $language->getCode(),
                $language->getCode()
            ))
            ->from(self::STATUS_TABLE, 'a');
    }
}
