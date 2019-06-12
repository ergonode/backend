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
    private const TABLE = 'attribute_group';

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
        return new DbalDataSet($this->getQuery());
    }

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('*')
            ->addSelect('(SELECT count(a.id) FROM attribute a JOIN attribute_group_attribute aga ON aga.attribute_id = a.id WHERE aga.attribute_group_id = g.id) AS elements_count')
            ->from(self::TABLE, 'g');
    }
}
