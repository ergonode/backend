<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
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
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;

class DbalOptionQuery implements OptionQueryInterface
{
    private const TABLE_OPTIONS = 'attribute_option';
    private const TABLE_ATTRIBUTE_OPTIONS = 'attribute_options';
    private const TABLE_VALUES = 'value_translation';

    private Connection $connection;

    private DbalDataSetFactory $dataSetFactory;

    private RelationshipsResolverInterface $relationshipsResolver;

    public function __construct(
        Connection $connection,
        DbalDataSetFactory $dataSetFactory,
        RelationshipsResolverInterface $relationshipsResolver
    ) {
        $this->connection = $connection;
        $this->dataSetFactory = $dataSetFactory;
        $this->relationshipsResolver = $relationshipsResolver;
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
            ->join(
                'o',
                self::TABLE_ATTRIBUTE_OPTIONS,
                'ao',
                'ao.option_id = o.id',
            )
            ->leftJoin(
                'o',
                self::TABLE_VALUES,
                'vt',
                'vt.value_id = o.value_id AND vt.language = :language',
            )
            ->andWhere($qb->expr()->eq('ao.attribute_id', ':id'))
            ->setParameter(':id', $attributeId->getValue())
            ->setParameter(':language', $language->getCode())
            ->orderBy('ao.index')
            ->execute()
            ->fetchAllKeyValue();
    }

    /**
     * @return array
     */
    public function getOptions(AttributeId $attributeId): array
    {
        $qb = $this->connection->createQueryBuilder();

        return $qb->select('id')
            ->from(self::TABLE_OPTIONS, 'o')
            ->join(
                'o',
                self::TABLE_ATTRIBUTE_OPTIONS,
                'ao',
                'ao.option_id = o.id',
            )
            ->where($qb->expr()->eq('ao.attribute_id', ':attribute'))
            ->setParameter(':attribute', $attributeId->getValue())
            ->orderBy('ao.index')
            ->execute()
            ->fetchFirstColumn();
    }

    /**
     * @return array
     */
    public function getAll(?AttributeId $attributeId = null, bool $withRelations = false): array
    {
        $qb = $this->getQuery();

        $qb->select('o.id, o.key as code, value_id')
            ->join('o', self::TABLE_ATTRIBUTE_OPTIONS, 'ao', 'ao.option_id = o.id');
        if ($attributeId) {
            $qb
                ->andWhere($qb->expr()->eq('ao.attribute_id', ':id'))
                ->setParameter(':id', $attributeId->getValue());
        }
        $records = $qb
            ->orderBy('ao.attribute_id, ao.index')
            ->execute()
            ->fetchAll();

        $result = [];
        foreach ($records as $record) {
            $value = $this->getValue($record['value_id']);

            $item = [
                'id' => $record['id'],
                'code' => $record['code'],
                'label' => !empty($value) ? $value : [],
            ];

            if ($withRelations) {
                $item['relations'] = (bool) $this->relationshipsResolver->resolve(new AggregateId($item['id']));
            }

            $result[] = $item;
        }

        return $result;
    }

    public function findIdByAttributeIdAndCode(AttributeId $id, OptionKey $code): ?AggregateId
    {
        $qb = $this->getQuery();

        $result = $qb
            ->select('o.id')
            ->join(
                'o',
                self::TABLE_ATTRIBUTE_OPTIONS,
                'ao',
                'ao.option_id = o.id',
            )
            ->andWhere($qb->expr()->eq('ao.attribute_id', ':id'))
            ->andWhere($qb->expr()->eq('o.key', ':code'))
            ->setParameter(':id', $id->getValue())
            ->setParameter(':code', $code->getValue())
            ->orderBy('ao.index')
            ->execute()
            ->fetchOne();

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
            ->fetchOne();

        return $key ?
            new OptionKey($key) :
            null;
    }

    public function getDataSet(AttributeId $attributeId, Language $language): DataSetInterface
    {
        $qb = $this->getQuery();
        $qb->select('o.id, o.key AS code, ao.attribute_id');
        $qb->join(
            'o',
            self::TABLE_ATTRIBUTE_OPTIONS,
            'ao',
            'ao.option_id = o.id',
        );
        $qb->where($qb->expr()->eq('ao.attribute_id', '\''.$attributeId->getValue()).'\'');

        $result = $this->connection->createQueryBuilder();
        $result->select('*');
        $result->from(sprintf('(%s)', $qb->getSQL()), 't');

        return $this->dataSetFactory->create($result);
    }

    public function getAttributeIdByOptionId(AggregateId $id): ?AttributeId
    {
        $qb = $this->connection->createQueryBuilder()
            ->select('ao.attribute_id')
            ->from(self::TABLE_ATTRIBUTE_OPTIONS, 'ao');

        $res = $qb->where($qb->expr()->eq('ao.option_id', '\''.$id->getValue()).'\'')
            ->execute()
            ->fetchOne();
        if ($res) {
            return new AttributeId($res);
        }

        return null;
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
            ->fetchAllKeyValue();
    }

    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('*')
            ->from(self::TABLE_OPTIONS, 'o');
    }
}
