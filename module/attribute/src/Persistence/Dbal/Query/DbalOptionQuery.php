<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;

/**
 */
class DbalOptionQuery implements OptionQueryInterface
{
    private const TABLE_OPTIONS = 'attribute_option';
    private const TABLE_VALUES = 'value_translation';

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
     * @param AttributeId $attributeId
     * @param Language    $language
     *
     * @return array
     */
    public function getList(AttributeId $attributeId, Language $language): array
    {
        $qb = $this->getQuery();

        return $qb->select('o.id, vt.value')
            ->leftJoin('o', self::TABLE_VALUES, 'vt', 'vt.value_id = o.value_id')
            ->andWhere($qb->expr()->eq('o.attribute_id', ':id'))
            ->andWhere($qb->expr()->orX(
                $qb->expr()->eq('vt.language', ':language'),
                $qb->expr()->isNull('vt.language')
            ))
            ->setParameter(':id', $attributeId->getValue())
            ->setParameter(':language', $language->getCode())
            ->orderBy('vt.language')
            ->execute()
            ->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    /**
     * @param AttributeId $id
     * @param OptionKey   $code
     *
     * @return AggregateId|null
     */
    public function findIdByAttributeIdAndCode(AttributeId $id, OptionKey $code): ?AggregateId
    {
        $qb = $this->getQuery();

        $result = $qb
            ->select('o.id')
            ->andWhere($qb->expr()->eq('o.attribute_id', ':id'))
            ->andWhere($qb->expr()->eq('o.key', ':code'))
            ->setParameter(':id', $id->getValue())
            ->setParameter(':code', $code->getValue())
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);

        if ($result) {
            return new AggregateId($result);
        }

        return null;
    }

    /**
     * @param AttributeId $attributeId
     * @param Language    $language
     *
     * @return DataSetInterface
     */
    public function getDataSet(AttributeId $attributeId, Language $language): DataSetInterface
    {
        $qb = $this->getQuery();
        $qb->select('o.id, o.key AS code, o.attribute_id');
        $qb->where($qb->expr()->eq('attribute_id', '\''.$attributeId->getValue()).'\'');

        $result = $this->connection->createQueryBuilder();
        $result->select('*');
        $result->from(sprintf('(%s)', $qb->getSQL()), 't');

        return new DbalDataSet($result);
    }

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('*')
            ->from(self::TABLE_OPTIONS, 'o');
    }
}
