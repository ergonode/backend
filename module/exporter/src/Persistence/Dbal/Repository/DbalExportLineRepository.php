<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Persistence\Dbal\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\Exporter\Persistence\Dbal\Repository\Factory\ExportLineFactory;
use Ergonode\Exporter\Persistence\Dbal\Repository\Mapper\ExportLineMapper;
use Ergonode\Exporter\Domain\Repository\ExportLineRepositoryInterface;
use Ergonode\Exporter\Domain\Entity\ExportLine;
use Ergonode\SharedKernel\Domain\AggregateId;

/**
 */
class DbalExportLineRepository implements ExportLineRepositoryInterface
{
    private const TABLE = 'exporter.export_line';
    private const FIELDS = [
        'export_id',
        'object_id',
        'message',
        'processed_at',
    ];

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var ExportLineFactory
     */
    private ExportLineFactory $factory;

    /**
     * @var ExportLineMapper
     */
    private ExportLineMapper $mapper;

    /**
     * @param Connection        $connection
     * @param ExportLineFactory $factory
     * @param ExportLineMapper  $mapper
     */
    public function __construct(Connection $connection, ExportLineFactory $factory, ExportLineMapper $mapper)
    {
        $this->connection = $connection;
        $this->factory = $factory;
        $this->mapper = $mapper;
    }

    /**
     * @param ExportId    $exportId
     * @param AggregateId $objectId
     *
     * @return ExportLine|null
     *
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
     * @param ExportLine $line
     *
     * @throws DBALException
     */
    public function save(ExportLine $line): void
    {
        if ($this->exists($line->getExportId(), $line->getObjectId())) {
            $this->update($line);
        } else {
            $this->insert($line);
        }
    }

    /**
     * @param ExportId    $exportId
     * @param AggregateId $objectId
     *
     * @return bool
     */
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
     * @param ExportLine $line
     *
     * @throws DBALException
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
            ]
        );
    }

    /**
     * @param ExportLine $line
     *
     * @throws DBALException
     */
    public function insert(ExportLine $line): void
    {
        $lineArray = $this->mapper->map($line);

        $this->connection->insert(
            self::TABLE,
            $lineArray
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
