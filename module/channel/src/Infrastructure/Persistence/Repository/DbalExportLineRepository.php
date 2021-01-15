<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Infrastructure\Persistence\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Types;
use Ergonode\Channel\Domain\Entity\ExportLine;
use Ergonode\Channel\Domain\Repository\ExportLineRepositoryInterface;
use Ergonode\Channel\Infrastructure\Persistence\Repository\Factory\DbalExportLineFactory;
use Ergonode\Channel\Infrastructure\Persistence\Repository\Mapper\DbalExportLineMapper;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\SharedKernel\Domain\AggregateId;

class DbalExportLineRepository implements ExportLineRepositoryInterface
{
    private const TABLE = 'exporter.export_line';
    private const FIELDS = [
        'export_id',
        'object_id',
        'message',
        'processed_at',
    ];

    private Connection $connection;

    private DbalExportLineFactory $factory;

    private DbalExportLineMapper $mapper;

    public function __construct(Connection $connection, DbalExportLineFactory $factory, DbalExportLineMapper $mapper)
    {
        $this->connection = $connection;
        $this->factory = $factory;
        $this->mapper = $mapper;
    }

    /**
     * @throws \ReflectionException
     */
    public function load(ExportId $exportId, AggregateId $objectId): ?ExportLine
    {
        $qb = $this->getQuery();
        $record = $qb
            ->andWhere($qb->expr()->eq('export_id', ':exportId'))
            ->andWhere($qb->expr()->eq('object_id', ':objectId'))
            ->setParameter(':exportId', $exportId->getValue())
            ->setParameter(':objectId', $objectId->getValue())
            ->execute()
            ->fetch();

        if ($record) {
            return $this->factory->create($record);
        }

        return null;
    }

    /**
     * @throws DBALException
     * @throws \JsonException
     */
    public function save(ExportLine $line): void
    {
        if ($this->exists($line->getExportId(), $line->getObjectId())) {
            $this->update($line);
        } else {
            $this->insert($line);
        }
    }

    public function exists(ExportId $exportId, AggregateId $objectId): bool
    {
        $query = $this->connection->createQueryBuilder();
        $result = $query->select(1)
            ->from(self::TABLE)
            ->andWhere($query->expr()->eq('export_id', ':exportId'))
            ->andWhere($query->expr()->eq('object_id', ':objectId'))
            ->setParameter(':exportId', $exportId->getValue())
            ->setParameter(':objectId', $objectId->getValue())
            ->execute()
            ->rowCount();

        if ($result) {
            return true;
        }

        return false;
    }

    /**
     * @throws DBALException
     * @throws \JsonException
     */
    public function update(ExportLine $line): void
    {
        $lineArray = $this->mapper->map($line);

        $this->connection->update(
            self::TABLE,
            $lineArray,
            [
                'export_id' => $line->getExportId()->getValue(),
                'object_id' => $line->getObjectId()->getValue(),
            ],
            [
                'processed_at' => Types::DATETIMETZ_MUTABLE,
            ],
        );
    }

    /**
     * @throws DBALException
     * @throws \JsonException
     */
    public function insert(ExportLine $line): void
    {
        $lineArray = $this->mapper->map($line);

        $this->connection->insert(
            self::TABLE,
            $lineArray,
            [
                'processed_at' => Types::DATETIMETZ_MUTABLE,
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
