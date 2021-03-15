<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\Factory\DbalDataSetFactory;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\AggregateId;

class DbalOptionQuery implements OptionQueryInterface
{
    private const TABLE_OPTIONS = 'attribute_option';
    private const TABLE_VALUES = 'value_translation';

    private Connection $connection;

    private DbalDataSetFactory $dataSetFactory;

    public function __construct(Connection $connection, DbalDataSetFactory $dataSetFactory)
    {
        $this->connection = $connection;
        $this->dataSetFactory = $dataSetFactory;
    }

    /**
     * @return array
     */
    public function getList(AttributeId $attributeId, Language $language): array
    {
        $qb = $this->getQuery();
        //TODO it's not the solution we've been waiting for, but the one we've had time for.
        return $qb->select('o.id')
            ->addSelect('COALESCE(vt.value, CONCAT(\'#\', o.key)) AS value')
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
     * @return array
     */
    public function getOptions(AttributeId $attributeId): array
    {
        $qb = $this->connection->createQueryBuilder();

        return $qb->select('id')
            ->from(self::TABLE_OPTIONS, 'o')
            ->where($qb->expr()->eq('attribute_id', ':attribute'))
            ->setParameter(':attribute', $attributeId->getValue())
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * @return array
     */
    public function getAll(?AttributeId $attributeId = null): array
    {
        $qb = $this->getQuery();

        $qb->select('o.id, o.key as code, value_id');
        if ($attributeId) {
            $qb
                ->andWhere($qb->expr()->eq('o.attribute_id', ':id'))
                ->setParameter(':id', $attributeId->getValue());
        }
        $records = $qb
            ->orderBy('o.attribute_id, o.key')
            ->execute()
            ->fetchAll();

        $result = [];
        foreach ($records as $record) {
            $value = $this->getValue($record['value_id']);
            $result[] = [
                'id' => $record['id'],
                'code' => $record['code'],
                'label' => !empty($value) ? $value : [],
            ];
        }

        return $result;
    }

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

    public function findKey(AggregateId $id): ?OptionKey
    {
        $qb = $this->getQuery();

        $key = $qb
            ->select('o.key')
            ->where($qb->expr()->eq('o.id', ':id'))
            ->setParameter('id', $id->getValue())
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);

        return $key ?
            new OptionKey($key) :
            null;
    }

    public function getDataSet(AttributeId $attributeId, Language $language): DataSetInterface
    {
        $qb = $this->getQuery();
        $qb->select('o.id, o.key AS code, o.attribute_id');
        $qb->where($qb->expr()->eq('attribute_id', '\''.$attributeId->getValue()).'\'');

        $result = $this->connection->createQueryBuilder();
        $result->select('*');
        $result->from(sprintf('(%s)', $qb->getSQL()), 't');

        return $this->dataSetFactory->create($result);
    }

    /**
     * @return array
     */
    private function getValue(string $valueId): array
    {
        $qb = $this->connection->createQueryBuilder();

        return $qb->select('language, value')
            ->from(self::TABLE_VALUES)
            ->where($qb->expr()->eq('value_id', ':id'))
            ->setParameter(':id', $valueId)
            ->orderBy('language')
            ->execute()
            ->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('*')
            ->from(self::TABLE_OPTIONS, 'o');
    }
}
