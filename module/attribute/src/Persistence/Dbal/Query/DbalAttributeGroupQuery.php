<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\Query\AttributeGroupQueryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeGroupCode;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;

/**
 */
class DbalAttributeGroupQuery implements AttributeGroupQueryInterface
{
    private const TABLE = 'attribute_group';
    private const RELATION_TABLE = 'attribute_group_attribute';

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
     * @return array
     */
    public function getAttributeGroups(Language $language): array
    {
        return $this->connection->createQueryBuilder()
            ->select(sprintf('ag.id, ag.name->>\'%s\' AS label', $language->getCode()))
            ->addSelect(
                '(SELECT count(*) FROM attribute_group_attribute '.
                ' WHERE attribute_group_id = ag.id) AS elements_count'
            )
            ->from(self::TABLE, 'ag')
            ->execute()
            ->fetchAll();
    }

    /**
     * @param AttributeGroupId $id
     *
     * @return AttributeId[]
     */
    public function getAllAttributes(AttributeGroupId $id): array
    {
        $qb = $this->connection->createQueryBuilder();

        $records = $qb->select('aga.attribute_id')
            ->from(self::RELATION_TABLE, 'aga')
            ->where($qb->expr()->eq('attribute_group_id', ':id'))
            ->setParameter(':id', $id->getValue())
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);

        $result = [];
        foreach ($records as $record) {
            $result[] = new AttributeId($record);
        }

        return $result;
    }

    /**
     * @param Language $language
     *
     * @return DataSetInterface
     */
    public function getDataSet(Language $language): DataSetInterface
    {
        $query = $this->connection->createQueryBuilder();
        $query
            ->select('*')
            ->from(sprintf('(%s)', $this->getQuery($language)->getSQL()), 't');

        return new DbalDataSet($query);
    }

    /**
     * @param AttributeGroupCode $code
     *
     * @return bool
     */
    public function checkAttributeGroupExistsByCode(AttributeGroupCode $code): bool
    {
        $qb = $this->connection->createQueryBuilder();
        $result = $qb->select('id')
            ->from(self::TABLE, 'ag')
            ->where($qb->expr()->eq('code', ':code'))
            ->setParameter(':code', $code->getValue())
            ->execute()
            ->rowCount();

        if ($result) {
            return true;
        }

        return false;
    }

    /**
     * @param Language $language
     *
     * @return QueryBuilder
     */
    private function getQuery(Language $language): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(sprintf('ag.id, ag.code, ag.name->>\'%s\' AS name', $language->getCode()))
            ->addSelect(
                '(SELECT count(*) FROM attribute_group_attribute  WHERE attribute_group_id = ag.id)'.
                ' AS elements_count'
            )
            ->from(self::TABLE, 'ag');
    }
}
