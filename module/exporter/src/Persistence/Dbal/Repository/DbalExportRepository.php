<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Persistence\Dbal\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\Exporter\Domain\Repository\ExportRepositoryInterface;
use Ergonode\Exporter\Persistence\Dbal\Repository\Factory\ExportFactory;
use Ergonode\Exporter\Persistence\Dbal\Repository\Mapper\ExportMapper;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;

/**
 */
class DbalExportRepository implements ExportRepositoryInterface
{
    private const TABLE = 'exporter.export';
    private const FIELDS = [
        'id',
        'status',
        'channel_id',
        'started_at',
        'ended_at',
    ];

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var ExportFactory
     */
    private ExportFactory $factory;

    /**
     * @var ExportMapper
     */
    private ExportMapper $mapper;

    /**
     * @param Connection    $connection
     * @param ExportFactory $factory
     * @param ExportMapper  $mapper
     */
    public function __construct(Connection $connection, ExportFactory $factory, ExportMapper $mapper)
    {
        $this->connection = $connection;
        $this->factory = $factory;
        $this->mapper = $mapper;
    }

    /**
     * @param ExportId $id
     *
     * @return Export|null
     *
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
     * @param Export $export
     *
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

    /**
     * @param ExportId $id
     *
     * @return bool
     */
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

    /**
     * @param Export $export
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function update(Export $export): void
    {
        $exportArray = $this->mapper->map($export);
        $exportArray['updated_at'] = date('Y-m-d H:i:s');

        $this->connection->update(
            self::TABLE,
            $exportArray,
            [
                'id' => $export->getId()->getValue(),
            ]
        );
    }

    /**
     * @param Export $export
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function insert(Export $export): void
    {
        $exportArray = $this->mapper->map($export);
        $exportArray['created_at'] = date('Y-m-d H:i:s');
        $exportArray['updated_at'] = date('Y-m-d H:i:s');

        $this->connection->insert(
            self::TABLE,
            $exportArray
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
