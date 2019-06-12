<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Persistence\Dbal\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Exception\InvalidArgumentException;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Importer\Domain\Entity\AbstractImport;
use Ergonode\Importer\Domain\Entity\ImportId;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\Importer\Persistence\Dbal\Repository\Factory\ImportFactory;
use Ergonode\Importer\Persistence\Dbal\Repository\Mapper\ImportMapper;

/**
 */
class DbalImportRepository implements ImportRepositoryInterface
{
    private const TABLE = 'importer.import';
    private const FIELDS = [
        'id',
        'name',
        'type',
        'status',
        'options',
        'reason',
    ];

        /**
     * @var Connection
     */
    private $connection;

    /**
     * @var ImportFactory
     */
    private $factory;

    /**
     * @var ImportMapper
     */
    private $mapper;

    /**
     * @param Connection    $connection
     * @param ImportFactory $factory
     * @param ImportMapper  $mapper
     */
    public function __construct(Connection $connection, ImportFactory $factory, ImportMapper $mapper)
    {
        $this->connection = $connection;
        $this->factory = $factory;
        $this->mapper = $mapper;
    }

    /**
     * @param ImportId $id
     *
     * @return AbstractImport|null
     *
     * @throws \ReflectionException
     */
    public function load(ImportId $id): ?AbstractImport
    {
        $qb = $this->getQuery();
        $record = $qb->where($qb->expr()->eq('id', ':id'))
            ->setParameter(':id', $id->getValue())
            ->execute()
            ->fetch();

        if ($record) {
            return $this->factory->create($record);
        }

        return null;
    }

    /**
     * @param AbstractImport $import
     *
     * @throws DBALException
     */
    public function save(AbstractImport $import): void
    {
        if ($this->exists($import->getId())) {
            $this->update($import);
        } else {
            $this->insert($import);
        }
    }

    /**
     * @param ImportId $id
     *
     * @return bool
     */
    public function exists(ImportId $id): bool
    {
        $query = $this->connection->createQueryBuilder();
        $result = $query->select(1)
            ->from(self::TABLE)
            ->where($query->expr()->eq('id', ':id'))
            ->setParameter(':id', $id->getValue())
            ->execute()
            ->rowCount();

        if ($result) {
            return true;
        }

        return false;
    }

    /**
     * @param AbstractImport $import
     *
     * @throws DBALException
     * @throws InvalidArgumentException
     */
    public function remove(AbstractImport $import): void
    {
        $this->connection->delete(
            self::TABLE,
            [
                'id' => $import->getId()->getValue(),
            ]
        );
    }

    /**
     * @param AbstractImport $import
     *
     * @throws DBALException
     */
    private function update(AbstractImport $import): void
    {
        $importArray = $this->mapper->map($import);
        $importArray['updated_at'] = date('Y-m-d H:i:s');

        $this->connection->update(
            self::TABLE,
            $importArray,
            [
                'id' => $import->getId()->getValue(),
            ]
        );
    }

    /**
     * @param AbstractImport $import
     *
     * @throws DBALException
     */
    private function insert(AbstractImport $import): void
    {
        $importArray = $this->mapper->map($import);
        $importArray['created_at'] = date('Y-m-d H:i:s');
        $importArray['updated_at'] = date('Y-m-d H:i:s');

        $this->connection->insert(
            self::TABLE,
            $importArray
        );
    }

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(self::FIELDS)
            ->from(self::TABLE);
    }
}
