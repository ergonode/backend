<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Persistence\Dbal\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\Importer\Domain\Entity\ImportError;
use Ergonode\Importer\Domain\Repository\ImportErrorRepositoryInterface;
use Ergonode\Importer\Persistence\Dbal\Repository\Factory\ImportErrorFactory;
use Ergonode\Importer\Persistence\Dbal\Repository\Mapper\ImportErrorMapper;

/**
 */
class DbalImportErrorRepository implements ImportErrorRepositoryInterface
{
    private const TABLE = 'importer.import_error';
    private const FIELDS = [
        'import_id',
        'line',
        'step',
        'message',
        'created_at',
    ];

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var ImportErrorFactory
     */
    private ImportErrorFactory $factory;

    /**
     * @var ImportErrorMapper
     */
    private ImportErrorMapper $mapper;

    /**
     * @param Connection         $connection
     * @param ImportErrorFactory $factory
     * @param ImportErrorMapper  $mapper
     */
    public function __construct(Connection $connection, ImportErrorFactory $factory, ImportErrorMapper $mapper)
    {
        $this->connection = $connection;
        $this->factory = $factory;
        $this->mapper = $mapper;
    }

    /**
     * @param ImportId $importId
     * @param int      $step
     * @param int      $line
     *
     * @return ImportError|null
     *
     * @throws \ReflectionException
     */
    public function load(ImportId $importId, int $step, int $line): ?ImportError
    {
        $qb = $this->getQuery();
        $record = $qb
            ->andWhere($qb->expr()->eq('import_id', ':id'))
            ->andWhere($qb->expr()->eq('line', ':line'))
            ->andWhere($qb->expr()->eq('step', ':step'))
            ->setParameter(':id', $importId->getValue())
            ->setParameter(':line', $line)
            ->setParameter(':step', $step)
            ->orderBy('line', 'ASC')
            ->execute()
            ->fetch();

        if ($record) {
            return $this->factory->create($record);
        }

        return null;
    }

    /**
     * @param ImportError $importLine
     *
     * @throws DBALException
     */
    public function save(ImportError $importLine): void
    {
        $this->insert($importLine);
    }

    /**
     * @param ImportId $id
     * @param int      $step
     * @param int      $line
     *
     * @return bool
     */
    public function exists(ImportId $id, int $step, int $line): bool
    {
        $query = $this->connection->createQueryBuilder();
        $result = $query->select(1)
            ->from(self::TABLE)
            ->andWhere($query->expr()->eq('import_id', ':id'))
            ->andWhere($query->expr()->eq('line', ':line'))
            ->andWhere($query->expr()->eq('step', ':step'))
            ->setParameter(':id', $id->getValue())
            ->setParameter(':line', $line)
            ->setParameter(':step', $step)
            ->execute()
            ->rowCount();

        if ($result) {
            return true;
        }

        return false;
    }

    /**
     * @param ImportId $importId
     * @param int      $line
     *
     * @throws DBALException
     */
    public function remove(ImportId $importId, int $line): void
    {
        $this->connection->delete(
            self::TABLE,
            [
                'import_id' => $importId->getValue(),
                'line' => $line,
            ]
        );
    }

    /**
     * @param ImportError $importLine
     *
     * @throws DBALException
     */
    public function insert(ImportError $importLine): void
    {
        $importLineArray = $this->mapper->map($importLine);

        $this->connection->insert(
            self::TABLE,
            $importLineArray
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
