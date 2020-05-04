<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
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

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * @param Connection          $connection
     * @param TranslatorInterface $translator
     */
    public function __construct(Connection $connection, TranslatorInterface $translator)
    {
        $this->connection = $connection;
        $this->translator = $translator;
    }

    /**
     * @return DataSetInterface
     */
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

        return new DbalDataSet($result);
    }

    /**
     * @param Language $language
     *
     * @return array
     */
    public function getLanguageNodeInfo(Language $language): array
    {
        $qb = $this->connection->createQueryBuilder();

        return $qb->select('code, lft, rgt')
            ->from(self::TABLE_TREE)
            ->where($qb->expr()->eq('code', ':code'))
            ->setParameter(':code', $language->getCode())
            ->execute()
            ->fetch();
    }

    /**
     * @param Language $language
     *
     * @return Language[]
     */
    public function getInheritancePath(Language $language): array
    {
        $result = [];
        $qb = $this->connection->createQueryBuilder();
        $lft = $qb->select('lft')
            ->from(self::TABLE_TREE)
            ->where($qb->expr()->eq('code', ':code'))
            ->setParameter(':code', $language->getCode())
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);

        if (false !== $lft) {
            $qb = $this->connection->createQueryBuilder();
            $records = $qb->select('code')
                ->from(self::TABLE_TREE)
                ->orderBy('lft', 'ASC')
                ->where($qb->expr()->lt('lft', ':lft'))
                ->setParameter(':lft', $lft)
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
     * @param string $code
     *
     * @return array
     */
    public function getLanguage(string $code): array
    {
        $qb = $this->getQuery(self::ALL_FIELDS);

        return $qb
            ->where($qb->expr()->eq('iso', ':iso'))
            ->setParameter(':iso', $code)
            ->execute()
            ->fetchAll();
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
     * @param string|null $search
     * @param int|null    $limit
     * @param string|null $field
     * @param string|null $order
     *
     * @return array
     */
    public function autocomplete(
        string $search = null,
        int $limit = null,
        string $field = null,
        ?string $order = 'ASC'
    ): array {
        $query = $this->connection->createQueryBuilder()
            ->select(self::ALL_FIELDS)
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

        foreach ($records as $key => $record) {
            $records[$key]['name'] = $this->translator->trans($records[$key]['name'], [], 'language');
        }

        return $records;
    }

    /**
     * @param $fields
     *
     * @return QueryBuilder
     */
    private function getQuery($fields): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select($fields)
            ->from(self::TABLE);
    }
}
