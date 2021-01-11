<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\Factory\DbalDataSetFactory;
use Symfony\Contracts\Translation\TranslatorInterface;

class DbalLanguageQuery implements LanguageQueryInterface
{
    private const TABLE = 'language';
    private const TABLE_TREE = 'language_tree';
    private const ALL_FIELDS = [
        'id',
        'iso AS code',
        'iso AS name',
        'active',
    ];

    private const CODE_FIELD = [
        'iso AS code',
    ];

    private const DICTIONARY_FIELD = [
        'iso',
        'iso as name',
    ];

    private Connection $connection;

    private TranslatorInterface $translator;

    private DbalDataSetFactory $dataSetFactory;

    public function __construct(
        Connection $connection,
        TranslatorInterface $translator,
        DbalDataSetFactory $dataSetFactory
    ) {
        $this->connection = $connection;
        $this->translator = $translator;
        $this->dataSetFactory = $dataSetFactory;
    }

    public function getDataSet(): DataSetInterface
    {
        $query = $this->connection->createQueryBuilder()
            ->select('id, code, code AS name, active')
            ->from(sprintf(
                '(SELECT %s FROM %s)',
                implode(', ', self::ALL_FIELDS),
                self::TABLE
            ), 'l');

        $result = $this->connection->createQueryBuilder();
        $result->select('*');
        $result->from(sprintf('(%s)', $query->getSQL()), 't');

        return $this->dataSetFactory->create($result);
    }

    /**
     * @return array
     */
    public function getLanguageNodeInfo(Language $language): ?array
    {
        $qb = $this->connection->createQueryBuilder();

        $result = $qb->select('code, lft, rgt')
            ->from(self::TABLE_TREE)
            ->where($qb->expr()->eq('code', ':code'))
            ->setParameter(':code', $language->getCode())
            ->execute()
            ->fetch();
        if (is_array($result)) {
            return $result;
        }

        return null;
    }

    /**
     * @return Language[]
     */
    public function getInheritancePath(Language $language): array
    {
        $result = [];
        $qb = $this->connection->createQueryBuilder();
        $position = $qb->select('lft, rgt')
            ->from(self::TABLE_TREE)
            ->where($qb->expr()->eq('code', ':code'))
            ->setParameter(':code', $language->getCode())
            ->execute()
            ->fetch();

        if ($position) {
            $qb = $this->connection->createQueryBuilder();
            $records = $qb->select('code')
                ->from(self::TABLE_TREE)
                ->orderBy('lft', 'ASC')
                ->where($qb->expr()->lt('lft', ':lft'))
                ->andWhere($qb->expr()->gt('rgt', ':rgt'))
                ->setParameter(':lft', $position['lft'])
                ->setParameter(':rgt', $position['rgt'])
                ->execute()
                ->fetchAll(\PDO::FETCH_COLUMN);

            foreach ($records as $record) {
                $result[] = new Language($record);
            }
        }

        $result[] = $language;

        return array_reverse($result);
    }

    /**
     * @return array
     */
    public function getLanguage(string $code): array
    {
        $qb = $this->getQuery(self::ALL_FIELDS);

        $result = $qb
            ->where($qb->expr()->eq('iso', ':iso'))
            ->setParameter(':iso', $code)
            ->execute()
            ->fetch();
        if (is_array($result)) {
            return $result;
        }

        return [];
    }

    /**
     * @return Language[]
     */
    public function getAll(): array
    {
        $records = $this->getQuery(self::CODE_FIELD)
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);

        $result = [];
        foreach ($records as $record) {
            $result[] = new Language($record);
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getDictionary(): array
    {
        return $this->getQuery(self::DICTIONARY_FIELD)
            ->execute()
            ->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    /**
     * @return array
     */
    public function getDictionaryActive(): array
    {
        $qb = $this->getQuery(self::DICTIONARY_FIELD);

        return $qb
            ->where($qb->expr()->eq('active', ':active'))
            ->setParameter(':active', true, \PDO::PARAM_BOOL)
            ->execute()
            ->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    /**
     * @return Language[]
     */
    public function getActive(): array
    {
        $qb = $this->getQuery(self::CODE_FIELD);

        $records = $qb
            ->where($qb->expr()->eq('active', ':active'))
            ->setParameter(':active', true, \PDO::PARAM_BOOL)
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);

        $result = [];
        foreach ($records as $record) {
            $result[] = new Language($record);
        }

        return $result;
    }

    /**
     * @return array
     */
    public function autocomplete(
        string $search = null,
        int $limit = null,
        string $field = null,
        ?string $order = 'ASC'
    ): array {
        $query = $this->connection->createQueryBuilder()
            ->select([
                'id',
                'iso AS code',
                'iso AS label',
            ])
            ->from(self::TABLE);

        if ($search) {
            $query->orWhere(\sprintf('iso ILIKE %s', $query->createNamedParameter(\sprintf('%%%s%%', $search))));
        }
        if ($field) {
            $query->orderBy($field, $order);
        }

        if ($limit) {
            $query->setMaxResults($limit);
        }

        $records = $query
            ->execute()
            ->fetchAll();

        foreach (array_keys($records) as $key) {
            $records[$key]['label'] = $this->translator->trans($records[$key]['label'], [], 'language');
        }

        return $records;
    }

    /**
     * @return array|null
     */
    public function getLanguageById(string $id): ?array
    {
        $qb = $this->getQuery(self::CODE_FIELD);

        $result = $qb
            ->where($qb->expr()->eq('id', ':id'))
            ->setParameter(':id', $id)
            ->execute()
            ->fetch();
        if ($result) {
            return $result;
        }

        return null;
    }

    public function getRootLanguage(): Language
    {
        $qb = $this->connection->createQueryBuilder();
        $result = $qb->select('lt.code')
            ->from(self::TABLE_TREE, 'lt')
            ->where($qb->expr()->eq('lft', 1))
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);

        return new Language($result);
    }

    /**
     * @param array $fields
     */
    private function getQuery(array $fields): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select($fields)
            ->from(self::TABLE);
    }
}
