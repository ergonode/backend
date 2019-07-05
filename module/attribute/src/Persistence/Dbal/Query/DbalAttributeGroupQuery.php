<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Attribute\Domain\Query\AttributeGroupQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;

/**
 */
class DbalAttributeGroupQuery implements AttributeGroupQueryInterface
{
    private const TABLE_ATTRIBUTE = 'attribute';
    private const TABLE_ATTRIBUTE_GROUP = 'attribute_group';
    private const TABLE_ATTRIBUTE_GROUP_ATTRIBUTE = 'attribute_group_attribute';

    /**
     * @var Connection
     */
    private $connection;

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
    public function getAttributeGroups(): array
    {
        $qb = $this->getQuery();

        return $qb
            ->execute()
            ->fetchAll();
    }

    /**
     * @param Language $language
     *
     * @return DataSetInterface
     */
    public function getDataSet(Language $language): DataSetInterface
    {
        $qb = $this->getQuery();
        $query = $this->connection->createQueryBuilder();
        $query->select('*')
             ->from(sprintf('(%s)', $qb->getSQL()), 't');

        return new DbalDataSet($query);
    }

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('count(*) AS elements_count, aga.attribute_group_id AS id, coalesce(ag.label, \'Not in Group\') AS label')
            ->from(self::TABLE_ATTRIBUTE_GROUP, 'ag')
            ->rightJoin('ag', self::TABLE_ATTRIBUTE_GROUP_ATTRIBUTE, 'aga', 'ag.id = aga.attribute_group_id')
            ->rightJoin('aga', self::TABLE_ATTRIBUTE, 'a', 'a.id = aga.attribute_id')
            ->groupBy('aga.attribute_group_id, ag.label');
    }
}
