<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Persistence\Dbal\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Importer\Domain\Entity\ImportId;
use Ergonode\Importer\Domain\Entity\ImportLine;
use Ergonode\Importer\Domain\Entity\ImportLineId;
use Ergonode\Importer\Domain\Repository\ImportLineRepositoryInterface;
use Ergonode\Importer\Persistence\Dbal\Repository\Factory\ImportLineFactory;
use Ergonode\Importer\Persistence\Dbal\Repository\Mapper\ImportLineMapper;

/**
 */
class DbalImportLineRepository implements ImportLineRepositoryInterface
{
    private const TABLE = 'importer.import_line';
    private const FIELDS = [
        'id',
        'import_id',
        'line',
    ];

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var ImportLineFactory
     */
    private $factory;

    /**
     * @var ImportLineMapper
     */
    private $mapper;

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
     * @param ImportLine $importLine
     *
     * @throws DBALException
     */
    public function save(ImportLine $importLine): void
    {
        if ($this->exists($importLine->getId())) {
            $this->update($importLine);
        } else {
            $this->insert($importLine);
        }
    }

    /**
     * @param ImportLineId $id
     *
     * @return bool
     */
    public function exists(ImportLineId $id): bool
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
     * @param ImportLine $importLine
     *
     * @throws DBALException
     * @throws \InvalidArgumentException
     */
    public function remove(ImportLine $importLine): void
    {
        $this->connection->delete(
            self::TABLE,
            [
                'id' => (string) $importLine->getId(),
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
                'id' => (string) $importLine->getId(),
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
     * @throws \ReflectionException
     */
    public function findCollectionByImport(ImportId $id): ArrayCollection
    {
        $qb = $this->getQuery();
        $records = $qb->where($qb->expr()->eq('import_id', ':id'))
            ->setParameter(':id', $id->getValue())
            ->orderBy('lp', 'ASC')
            ->execute()
            ->fetchAll();

        $result = new ArrayCollection();
        foreach ($records as $record) {
            $result->add($this->factory->create($record));
        }

        return $result;
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
