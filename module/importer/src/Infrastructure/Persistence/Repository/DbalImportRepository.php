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
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\SharedKernel\Domain\Aggregate\ImportLineId;

class DbalImportRepository implements ImportRepositoryInterface
{
    private const TABLE = 'importer.import';
    private const TABLE_ERROR = 'importer.import_error';
    private const TABLE_LINE = 'importer.import_line';
    private const FIELDS = [
        'id',
        'status',
        'source_id',
        'file',
        'started_at',
        'ended_at',
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

    public function addLine(ImportLineId $id, ImportId $importId, string $type): void
    {
        $this->connection->insert(
            self::TABLE_LINE,
            [
                'id' => $id->getValue(),
                'import_id' => $importId->getValue(),
                'type' => $type,
            ]
        );
    }

    public function markLineAsSuccess(ImportLineId $id, AggregateId $aggregateId): void
    {
        $this->connection->update(
            self::TABLE_LINE,
            [
                'processed_at' => new \DateTime(),
                'status' => Import::SUCCESS_LINE_STATUS,
                'object_id' => $aggregateId->getValue(),
            ],
            [
                'id' => $id->getValue(),
            ],
            [
                'processed_at' => Types::DATETIMETZ_MUTABLE,
            ]
        );
    }

    public function markLineAsFailure(ImportLineId $id): void
    {
        $this->connection->update(
            self::TABLE_LINE,
            [
                'processed_at' => new \DateTime(),
                'status' => Import::FAILURE_LINE_STATUS,
            ],
            [
                'id' => $id->getValue(),
            ],
            [
                'processed_at' => Types::DATETIMETZ_MUTABLE,
            ]
        );
    }

    /**
     * @param string[] $parameters
     *
     * @throws DBALException
     * @throws \JsonException
     */
    public function addError(ImportId $importId, string $message, array $parameters = []): void
    {
        $this->connection->insert(
            self::TABLE_ERROR,
            [
                'import_id' => $importId,
                'created_at' => new \DateTime(),
                'message' => $message,
                'parameters' => json_encode($parameters, JSON_THROW_ON_ERROR),
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
