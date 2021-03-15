<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Attribute\Domain\Query\AttributeGroupQueryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeGroupCode;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\Factory\DbalDataSetFactory;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

class DbalAttributeGroupQuery implements AttributeGroupQueryInterface
{
    private const TABLE = 'attribute_group';
    private const RELATION_TABLE = 'attribute_group_attribute';

    private Connection $connection;

    private DbalDataSetFactory $dataSetFactory;

    public function __construct(Connection $connection, DbalDataSetFactory $dataSetFactory)
    {
        $this->connection = $connection;
        $this->dataSetFactory = $dataSetFactory;
    }/**
     * @return array
     */
    public function getAttributeGroups(Language $language): array
    {
        return $this->connection->createQueryBuilder()
            ->select(sprintf('ag.id, ag.code, ag.name->>\'%s\' AS label', $language->getCode()))
            ->addSelect(
                '(SELECT count(*) FROM attribute_group_attribute '.
                ' WHERE attribute_group_id = ag.id) AS elements_count'
            )
            ->from(self::TABLE, 'ag')
            ->execute()
            ->fetchAll();
    }

    /**
     * @return array
     */
    public function getAttributeGroupIds(): array
    {
        return $this->connection->createQueryBuilder()
            ->select('ag.id')
            ->from(self::TABLE, 'ag')
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
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

    public function getDataSet(Language $language): DataSetInterface
    {
        $query = $this->connection->createQueryBuilder();
        $query
            ->select('*')
            ->from(sprintf('(%s)', $this->getQuery($language)->getSQL()), 't');

        return $this->dataSetFactory->create($query);
    }

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
     * @return array
     */
    public function autocomplete(
        Language $language,
        string $search = null,
        int $limit = null,
        string $field = null,
        ?string $order = 'ASC'
    ): array {
        $query = $this->connection->createQueryBuilder()
            ->select('id, code, COALESCE(name->>:language, null) as label')
            ->from(self::TABLE, 'ag')
            ->setParameter(':language', $language->getCode());

        if ($search) {
            $query->orWhere('code ILIKE :search');
            $query->setParameter(':search', '%'.$search.'%');
        }
        if ($field) {
            $query->orderBy($field, $order);
        }

        if ($limit) {
            $query->setMaxResults($limit);
        }

        return $query
            ->execute()
            ->fetchAll();
    }

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
