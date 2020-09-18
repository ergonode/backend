<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Persistence\Dbal\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Exception\InvalidArgumentException;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Types;
use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use Ergonode\Importer\Domain\Repository\SourceRepositoryInterface;
use Ergonode\Importer\Persistence\Dbal\Repository\Factory\SourceFactory;
use Ergonode\Importer\Persistence\Dbal\Repository\Mapper\SourceMapper;

/**
 */
class DbalSourceRepository implements SourceRepositoryInterface
{
    private const TABLE = 'importer.source';
    private const FIELDS = [
        'id',
        'type',
        'class',
        'configuration',
    ];

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var SourceFactory
     */
    private SourceFactory $factory;

    /**
     * @var SourceMapper
     */
    private SourceMapper $mapper;

    /**
     * @param Connection    $connection
     * @param SourceFactory $factory
     * @param SourceMapper  $mapper
     */
    public function __construct(Connection $connection, SourceFactory $factory, SourceMapper $mapper)
    {
        $this->connection = $connection;
        $this->factory = $factory;
        $this->mapper = $mapper;
    }

    /**
     * @param SourceId $id
     *
     * @return AbstractSource|null
     *
     * @throws \ReflectionException
     */
    public function load(SourceId $id): ?AbstractSource
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
     * @param AbstractSource $source
     *
     * @throws DBALException
     */
    public function save(AbstractSource $source): void
    {
        if ($this->exists($source->getId())) {
            $this->update($source);
        } else {
            $this->insert($source);
        }
    }

    /**
     * @param SourceId $id
     *
     * @return bool
     */
    public function exists(SourceId $id): bool
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
     * @param AbstractSource $source
     *
     * @throws DBALException
     * @throws InvalidArgumentException
     */
    public function delete(AbstractSource $source): void
    {
        $this->connection->delete(
            self::TABLE,
            [
                'id' => $source->getId()->getValue(),
            ]
        );
    }

    /**
     * @param AbstractSource $source
     *
     * @throws DBALException
     */
    private function update(AbstractSource $source): void
    {
        $sourceArray = $this->mapper->map($source);
        $sourceArray['updated_at'] = new \DateTime();

        $this->connection->update(
            self::TABLE,
            $sourceArray,
            [
                'id' => $source->getId()->getValue(),
            ],
            [
                'updated_at' => Types::DATETIMETZ_MUTABLE,
            ],
        );
    }

    /**
     * @param AbstractSource $source
     *
     * @throws DBALException
     */
    private function insert(AbstractSource $source): void
    {
        $sourceArray = $this->mapper->map($source);
        $sourceArray['created_at'] = $sourceArray['updated_at'] = new \DateTime();

        $this->connection->insert(
            self::TABLE,
            $sourceArray,
            [
                'created_at' => Types::DATETIMETZ_MUTABLE,
                'updated_at' => Types::DATETIMETZ_MUTABLE,
            ],
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
