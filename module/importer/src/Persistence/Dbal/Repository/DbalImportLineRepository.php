<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Persistence\Dbal\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Importer\Domain\Entity\ImportId;
use Ergonode\Importer\Domain\Entity\ImportLine;
use Ergonode\Importer\Domain\Repository\ImportLineRepositoryInterface;
use Ergonode\Importer\Persistence\Dbal\Repository\Factory\ImportLineFactory;
use Ergonode\Importer\Persistence\Dbal\Repository\Mapper\ImportLineMapper;

/**
 */
class DbalImportLineRepository implements ImportLineRepositoryInterface
{
    private const TABLE = 'importer.import_line';
    private const FIELDS = [
        'import_id',
        'line',
        'content',
        'message',
    ];

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var ImportLineFactory
     */
    private ImportLineFactory $factory;

    /**
     * @var ImportLineMapper
     */
    private ImportLineMapper $mapper;

    /**
     * @param Connection        $connection
     * @param ImportLineFactory $factory
     * @param ImportLineMapper  $mapper
     */
    public function __construct(Connection $connection, ImportLineFactory $factory, ImportLineMapper $mapper)
    {
        $this->connection = $connection;
        $this->factory = $factory;
        $this->mapper = $mapper;
    }

    /**
     * @param ImportId $importId
     * @param int      $line
     *
     * @return ImportLine|null
     *
     * @throws \ReflectionException
     */
    public function load(ImportId $importId, int $line): ?ImportLine
    {
        $qb = $this->getQuery();
        $record = $qb
            ->andWhere($qb->expr()->eq('import_id', ':id'))
            ->andWhere($qb->expr()->eq('line', ':line'))
            ->setParameter(':id', $importId->getValue())
            ->setParameter(':line', $line)
            ->orderBy('line', 'ASC')
            ->execute()
            ->fetch();



        if ($record) {
            return $this->factory->create($record);
        }

        return null;
    }

    /**
     * @param ImportLine $importLine
     *
     * @throws DBALException
     */
    public function save(ImportLine $importLine): void
    {
        if ($this->exists($importLine->getImportId(), $importLine->getLine())) {
            $this->update($importLine);
        } else {
            $this->insert($importLine);
        }
    }

    /**
     * @param ImportId $id
     * @param int      $line
     *
     * @return bool
     */
    public function exists(ImportId $id, int $line): bool
    {
        $query = $this->connection->createQueryBuilder();
        $result = $query->select(1)
            ->from(self::TABLE)
            ->andWhere($query->expr()->eq('import_id', ':id'))
            ->andWhere($query->expr()->eq('line', ':line'))
            ->setParameter(':id', $id->getValue())
            ->setParameter(':line', $line)
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
     * @param ImportLine $importLine
     *
     * @throws DBALException
     */
    public function update(ImportLine $importLine): void
    {
        $importLineArray = $this->mapper->map($importLine);
        $importLineArray['updated_at'] = date('Y-m-d H:i:s');

        $this->connection->update(
            self::TABLE,
            $importLineArray,
            [
                'import_id' => $importLine->getImportId()->getValue(),
                'line' => $importLine->getLine(),
            ]
        );
    }

    /**
     * @param ImportLine $importLine
     *
     * @throws DBALException
     */
    public function insert(ImportLine $importLine): void
    {
        $importLineArray = $this->mapper->map($importLine);
        $importLineArray['created_at'] = date('Y-m-d H:i:s');
        $importLineArray['updated_at'] = date('Y-m-d H:i:s');

        $this->connection->insert(
            self::TABLE,
            $importLineArray
        );
    }

    /**
     * @param ImportId $id
     *
     * @return ArrayCollection
     *
     * @throws \ReflectionException
     */
    public function findCollectionByImport(ImportId $id): ArrayCollection
    {
        $qb = $this->getQuery();
        $records = $qb->where($qb->expr()->eq('import_id', ':id'))
            ->setParameter(':id', $id->getValue())
            ->orderBy('line', 'ASC')
            ->execute()
            ->fetchAll();

        $result = new ArrayCollection();
        foreach ($records as $record) {
            $result->add($this->factory->create($record));
        }

        return $result;
    }

    /**
     * @param ImportId $id
     *
     * @return array
     */
    public function getKeys(ImportId $id): array
    {
        $qb = $this->getQuery();
        $record = $qb->where($qb->expr()->eq('import_id', ':id'))
            ->setParameter(':id', $id->getValue())
            ->orderBy('lp', 'ASC')
            ->execute()
            ->fetch();


        return array_keys($record);
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
