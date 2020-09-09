<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Attribute\Domain\ValueObject\OptionInterface;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Ergonode\Attribute\Domain\ValueObject\OptionValue\MultilingualOption;
use Ergonode\Attribute\Domain\ValueObject\OptionValue\StringOption;
use Ergonode\Attribute\Domain\View\AttributeViewModel;
use Ergonode\Attribute\Domain\View\Factory\AttributeViewModelFactory;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;

/**
 */
class DbalAttributeQuery implements AttributeQueryInterface
{
    private const TABLE = 'attribute';
    private const TABLE_PARAMETER = 'attribute_parameter';
    private const TABLE_VALUE_TRANSLATION = 'value_translation';
    private const TABLE_OPTIONS = 'attribute_option';
    private const TABLE_ATTRIBUTE_GROUPS = 'attribute_group_attribute';

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var AttributeViewModelFactory
     */
    private AttributeViewModelFactory $factory;

    /**
     * @param Connection                $connection
     * @param AttributeViewModelFactory $factory
     */
    public function __construct(Connection $connection, AttributeViewModelFactory $factory)
    {
        $this->connection = $connection;
        $this->factory = $factory;
    }

    /**
     * @param AttributeId $attributeId
     *
     * @return array|null
     */
    public function getAttribute(AttributeId $attributeId): ?array
    {
        $qb = $this->getQuery();

        $result = $qb
            ->addSelect('label', 'hint', 'placeholder', 'scope')
            ->where($qb->expr()->eq('id', ':id'))
            ->setParameter(':id', $attributeId->getValue())
            ->execute()
            ->fetch();

        $options = $this->getOptions($attributeId);
        $parameters = $this->getParameters($attributeId);

        if ($result) {
            $result['label'] = $this->getTranslations($result['label']);
            $result['hint'] = $this->getTranslations($result['hint']);
            $result['placeholder'] = $this->getTranslations($result['placeholder']);
            $result['groups'] = $this->getGroups($attributeId);
            if (!empty($parameters)) {
                $result['parameters'] = $parameters;
            }
            if (!empty($options)) {
                $result['options'] = $options;
            }

            return $result;
        }

        return null;
    }

    /**
     * @param AttributeId $id
     * @param OptionKey   $key
     *
     * @return OptionInterface|null
     */
    public function findAttributeOption(AttributeId $id, OptionKey $key): ?OptionInterface
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('language, value')
            ->from(self::TABLE_VALUE_TRANSLATION, 'vt')
            ->join('vt', self::TABLE_OPTIONS, 'ao', 'ao.value_id = vt.value_id')
            ->andWhere($qb->expr()->eq('ao.key', ':key'))
            ->andWhere($qb->expr()->eq('ao.attribute_id', ':id'))
            ->setParameter(':key', $key->getValue())
            ->setParameter(':id', $id->getValue());

        $records = $qb->execute()->fetchAll();
        if (!empty($records)) {
            $translation = new TranslatableString();
            foreach ($records as $record) {
                if (null === $record['language']) {
                    return new StringOption($record['value']);
                }

                $translation = $translation->add(new Language($record['language']), $record['value']);
            }

            return new MultilingualOption($translation);
        }

        return null;
    }

    /**
     * @param AttributeId $attributeId
     *
     * @return AttributeType|null
     */
    public function findAttributeType(AttributeId $attributeId): ?AttributeType
    {
        $qb = $this->getQuery();

        $result = $qb
            ->where($qb->expr()->eq('id', ':id'))
            ->setParameter(':id', $attributeId->getValue())
            ->execute()
            ->fetch();

        if ($result) {
            return new AttributeType($result['type']);
        }

        return null;
    }

    /**
     * @param AttributeCode $code
     *
     * @return AttributeViewModel
     */
    public function findAttributeByCode(AttributeCode $code): ?AttributeViewModel
    {
        $qb = $this->getQuery();
        $record = $qb
            ->select('id, code, type')
            ->where($qb->expr()->eq('code', ':code'))
            ->setParameter(':code', $code->getValue())
            ->execute()
            ->fetch();

        if ($record) {
            $attributeId = new AttributeId($record['id']);
            $record['groups'] = $this->getGroups($attributeId);

            return $this->factory->create($record);
        }

        return null;
    }

    /**
     * @param AttributeCode $code
     *
     * @return bool
     */
    public function checkAttributeExistsByCode(AttributeCode $code): bool
    {
        $qb = $this->getQuery();
        $result = $qb
            ->where($qb->expr()->eq('code', ':id'))
            ->setParameter(':id', $code->getValue())
            ->execute()
            ->rowCount();

        if ($result) {
            return true;
        }

        return false;
    }

    /**
     * @return string[]
     */
    public function getAllAttributeCodes(): array
    {
        return $this->getAttributeCodes();
    }

    /**
     * @param array $types
     *
     * @return string[]
     */
    public function getAttributeCodes(array $types = []): array
    {
        $qb = $this->getQuery()
            ->select('code');

        if ($types) {
            $qb->andWhere($qb->expr()->in('type', ':types'))
                ->setParameter(':types', $types, \Doctrine\DBAL\Connection::PARAM_STR_ARRAY);
        }

        return $qb
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * @param array $types
     *
     * @return string[]
     */
    public function getDictionary(array $types = []): array
    {
        $qb = $this->getQuery()
            ->select('id, code')
            ->andWhere('system = false');

        if ($types) {
            $qb->andWhere($qb->expr()->in('type', ':types'))
                ->setParameter(':types', $types, \Doctrine\DBAL\Connection::PARAM_STR_ARRAY);
        }

        return $qb
            ->execute()
            ->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    /**
     * @param UnitId $unitId
     *
     * @return array
     */
    public function findAttributeIdsByUnitId(UnitId $unitId): array
    {
        $qb = $this->getParametersQuery();

        $result = $qb
            ->select('p.attribute_id')
            ->where(sprintf('p.value@> \'"%s"\'', $unitId->getValue()))
            ->andWhere('p.type = \'unit\'')
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);

        if (false === $result) {
            $result = [];
        }

        foreach ($result as &$item) {
            $item = new AttributeId($item);
        }

        return $result;
    }

    /**
     * @param AttributeGroupId $id
     *
     * @return array
     */
    public function findAttributeIdsByAttributeGroupId(AttributeGroupId $id): array
    {
        $qb = $this->connection->createQueryBuilder();

        $result = $qb->select('attribute_id')
            ->from(self::TABLE_ATTRIBUTE_GROUPS, 'g')
            ->where($qb->expr()->eq('attribute_group_id', ':id'))
            ->setParameter(':id', $id->getValue())
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);

        if (false === $result) {
            $result = [];
        }

        foreach ($result as &$item) {
            $item = new AttributeId($item);
        }

        return $result;
    }

    /**
     * @param MultimediaId $id
     *
     * @return array
     */
    public function getMultimediaRelation(MultimediaId $id): array
    {
        $qb = $this->connection->createQueryBuilder();

        return $qb
            ->select('a.id, a.code')
            ->from('multimedia', 'm')
            ->join('m', 'value_translation', 'vt', 'vt.value = m.id::TEXT')
            ->join('vt', 'product_value', 'pv', 'pv.value_id = vt.id')
            ->join('pv', 'attribute', 'a', 'a.id = pv.attribute_id')
            ->groupBy('a.id, a.code')
            ->where($qb->expr()->eq('m.id', ':multimediaId'))
            ->setParameter(':multimediaId', $id->getValue())
            ->execute()
            ->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    /**
     * @param Language    $language
     * @param string|null $search
     * @param int|null    $limit
     * @param string|null $field
     * @param string|null $order
     *
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
            ->select('a.id, a.code')
            ->from(self::TABLE, 'a');

        $query->addSelect(sprintf(
            '(
                SELECT vt.value FROM value_translation vt 
                WHERE a.label = vt.value_id
                AND vt.language = \'%s\' 
                ) AS label',
            $language->getCode()
        ));

        if ($search) {
            $query->orWhere(\sprintf('code ILIKE %s', $query->createNamedParameter(\sprintf('%%%s%%', $search))));
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

    /**
     * @param AttributeId $attributeId
     *
     * @return array
     */
    private function getParameters(AttributeId $attributeId): array
    {
        $result = [];
        $qb = $this->getParametersQuery();

        $records = $qb
            ->where($qb->expr()->eq('attribute_id', ':id'))
            ->setParameter(':id', $attributeId->getValue())
            ->execute()
            ->fetchAll(\PDO::FETCH_KEY_PAIR);

        foreach ($records as $key => $record) {
            $result[$key] = json_decode($record, true);
        }

        return $result;
    }

    /**
     * @param string $valueId
     *
     * @return array
     */
    private function getTranslations(string $valueId): array
    {
        $qb = $this->getTranslationsQuery();

        $result = $qb
            ->where($qb->expr()->eq('value_id', ':id'))
            ->setParameter(':id', $valueId)
            ->execute()
            ->fetchAll(\PDO::FETCH_KEY_PAIR);

        return $result;
    }

    /**
     * @param AttributeId $attributeId
     *
     * @return array
     */
    private function getGroups(AttributeId $attributeId): array
    {
        $qb = $this->getGroupQuery();

        return $qb
            ->where($qb->expr()->eq('attribute_id', ':id'))
            ->setParameter(':id', $attributeId->getValue())
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * @param AttributeId $attributeId
     *
     * @return array
     */
    private function getOptions(AttributeId $attributeId): array
    {
        $result = [];
        $qb = $this->getOptionsQuery();

        $records = $qb
            ->where($qb->expr()->eq('attribute_id', ':id'))
            ->setParameter(':id', $attributeId->getValue())
            ->execute()
            ->fetchAll();

        foreach ($records as $record) {
            if (!isset($result[$record['key']])) {
                $result[$record['key']]['key'] = $record['key'];
                $result[$record['key']]['value'] = [];
            }
            if ($record['language']) {
                $result[$record['key']]['value'][$record['language']] = $record['value'];
            } else {
                $result[$record['key']]['value'] = $record['value'];
            }
        }

        return array_values($result);
    }

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('id, code, type')
            ->from(self::TABLE, 'a');
    }

    /**
     * @return QueryBuilder
     */
    private function getTranslationsQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('language, value')
            ->from(self::TABLE_VALUE_TRANSLATION, 't');
    }

    /**
     * @return QueryBuilder
     */
    private function getParametersQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('type, value')
            ->from(self::TABLE_PARAMETER, 'p');
    }

    /**
     * @return QueryBuilder
     */
    private function getOptionsQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('ao.value_id AS id, vt.language, vt.value, ao.key')
            ->leftJoin('ao', 'value_translation', 'vt', 'vt.value_id = ao.value_id')
            ->from(self::TABLE_OPTIONS, 'ao')
            ->orderBy('ao.key');
    }

    /**
     * @return QueryBuilder
     */
    private function getGroupQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('attribute_group_id')
            ->from(self::TABLE_ATTRIBUTE_GROUPS, 'g');
    }
}
