<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Persistence\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Exception\InvalidArgumentException;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Types;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\Importer\Infrastructure\Persistence\Repository\Factory\DbalImportFactory;
use Ergonode\Importer\Infrastructure\Persistence\Repository\Mapper\DbalImportMapper;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;

class DbalImportRepository implements ImportRepositoryInterface
{
    private const TABLE = 'importer.import';
    private const TABLE_ERROR = 'importer.import_error';
    private const FIELDS = [
        'id',
        'status',
        'source_id',
        'transformer_id',
        'file',
        'started_at',
        'ended_at',
        'records',
    ];

    private Connection $connection;

    private DbalImportFactory $factory;

    private DbalImportMapper $mapper;

    public function __construct(Connection $connection, DbalImportFactory $factory, DbalImportMapper $mapper)
    {
        $this->connection = $connection;
        $this->factory = $factory;
        $this->mapper = $mapper;
    }

    /**
     * @throws \ReflectionException
     */
    public function load(ImportId $id): ?Import
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
     * @throws DBALException
     */
    public function save(Import $import): void
    {
        if ($this->exists($import->getId())) {
            $this->update($import);
        } else {
            $this->insert($import);
        }
    }

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
     * @throws DBALException
     * @throws InvalidArgumentException
     */
    public function delete(Import $import): void
    {
        $this->connection->delete(
            self::TABLE,
            [
                'id' => $import->getId()->getValue(),
            ]
        );
    }

    /**
     * @throws DBALException
     */
    public function addError(ImportId $importId, string $message): void
    {
        $this->connection->insert(
            self::TABLE_ERROR,
            [
                'import_id' => $importId,
                'created_at' => new \DateTime(),
                'message' => $message,
            ],
            [
                'created_at' => Types::DATETIMETZ_MUTABLE,
            ],
        );
    }

    /**
     * @throws DBALException
     */
    private function update(Import $import): void
    {
        $importArray = $this->mapper->map($import);
        $importArray['updated_at'] = new \DateTime();

        $this->connection->update(
            self::TABLE,
            $importArray,
            [
                'id' => $import->getId()->getValue(),
            ],
            [
                'started_at' => Types::DATETIMETZ_MUTABLE,
                'ended_at' => Types::DATETIMETZ_MUTABLE,
                'updated_at' => Types::DATETIMETZ_MUTABLE,
            ],
        );
    }

    /**
     * @throws DBALException
     */
    private function insert(Import $import): void
    {
        $importArray = $this->mapper->map($import);
        $importArray['created_at'] = $importArray['updated_at'] = new \DateTime();

        $this->connection->insert(
            self::TABLE,
            $importArray,
            [
                'started_at' => Types::DATETIMETZ_MUTABLE,
                'ended_at' => Types::DATETIMETZ_MUTABLE,
                'created_at' => Types::DATETIMETZ_MUTABLE,
                'updated_at' => Types::DATETIMETZ_MUTABLE,
            ],
        );
    }

    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(self::FIELDS)
            ->from(self::TABLE);
    }
}
