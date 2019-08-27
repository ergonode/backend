<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Ergonode\Attribute\Domain\Query\AttributeGroupQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;

/**
 */
class DbalAttributeGroupQuery implements AttributeGroupQueryInterface
{
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
     * @return array
     */
    public function getAttributeGroups(): array
    {
        $query = $this->connection->createQueryBuilder();
        $query->select('*')
            ->from(sprintf('(%s)', $this->getSQL()), 't');

        return $query
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
        $query = $this->connection->createQueryBuilder();
        $query->select('*')
            ->from(sprintf('(%s)', $this->getSQL()), 't');

        return new DbalDataSet($query);
    }

    /**
     * @return string
     */
    private function getSQL(): string
    {
        return 'SELECT 
                count(aga.attribute_group_id) as elements_count,
                ag.id as id,
                ag.label
                FROM attribute_group ag
                LEFT JOIN attribute_group_attribute aga ON aga.attribute_group_id = ag.id
                GROUP BY aga.attribute_group_id, ag.label, ag.id
                UNION
                SELECT 
                count(*) as elements_count,
                null as id,
                \'Not in group\' as label
                FROM attribute a
                LEFT JOIN attribute_group_attribute aga ON aga.attribute_id = a.id
                WHERE aga.attribute_id IS NULL';
    }
}
