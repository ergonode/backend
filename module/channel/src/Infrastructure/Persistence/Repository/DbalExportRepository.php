<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Infrastructure\Persistence\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Types;
use Ergonode\Channel\Domain\Entity\Export;
use Ergonode\Channel\Domain\Repository\ExportRepositoryInterface;
use Ergonode\Channel\Domain\ValueObject\ExportLineId;
use Ergonode\Channel\Infrastructure\Persistence\Repository\Factory\DbalExportFactory;
use Ergonode\Channel\Infrastructure\Persistence\Repository\Mapper\DbalExportMapper;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Doctrine\DBAL\DBALException;
use Ergonode\SharedKernel\Domain\AggregateId;

class DbalExportRepository implements ExportRepositoryInterface
{
    private const TABLE = 'exporter.export';
    private const TABLE_LINE = 'exporter.export_line';
    private const TABLE_ERROR = 'exporter.export_error';
    private const FIELDS = [
        'id',
        'status',
        'channel_id',
        'started_at',
        'ended_at',
    ];

    private Connection $connection;

    private DbalExportFactory $factory;

    private DbalExportMapper $mapper;

    public function __construct(Connection $connection, DbalExportFactory $factory, DbalExportMapper $mapper)
    {
        $this->connection = $connection;
        $this->factory = $factory;
        $this->mapper = $mapper;
    }

    /**
     * @throws \ReflectionException
     */
    public function load(ExportId $id): ?Export
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
     * @throws \Doctrine\DBAL\DBALException
     */
    public function save(Export $export): void
    {
        if ($this->exists($export->getId())) {
            $this->update($export);
        } else {
            $this->insert($export);
        }
    }

    public function exists(ExportId $id): bool
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

    public function delete(Export $export): void
    {
        $this->connection->delete(
            self::TABLE,
            [
                'id' => $export->getId()->getValue(),
            ]
        );
    }

    public function addLine(ExportLineId $lineId, ExportId $exportId, AggregateId $objectId): void
    {
        $this->connection->insert(
            self::TABLE_LINE,
            [
                'id' => $lineId->getValue(),
                'export_id' => $exportId->getValue(),
                'object_id' => $objectId->getValue(),
            ]
        );
    }

    public function processLine(ExportLineId $lineId): void
    {
        $this->connection->update(
            self::TABLE_LINE,
            [
                'processed_at' => new \DateTime(),
            ],
            [
                'id' => $lineId->getValue(),
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
    public function addError(ExportId $exportId, string $message, array $parameters = []): void
    {
        $this->connection->insert(
            self::TABLE_ERROR,
            [
                'export_id' => $exportId,
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
     * @throws \Doctrine\DBAL\DBALException
     */
    private function update(Export $export): void
    {
        $exportArray = $this->mapper->map($export);
        $exportArray['updated_at'] = new \DateTime();

        $this->connection->update(
            self::TABLE,
            $exportArray,
            [
                'id' => $export->getId()->getValue(),
            ],
            [
                'started_at' => Types::DATETIMETZ_MUTABLE,
                'ended_at' => Types::DATETIMETZ_MUTABLE,
                'updated_at' => Types::DATETIMETZ_MUTABLE,
            ],
        );
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    private function insert(Export $export): void
    {
        $exportArray = $this->mapper->map($export);
        $exportArray['created_at'] = $exportArray['updated_at'] = new \DateTime();

        $this->connection->insert(
            self::TABLE,
            $exportArray,
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
