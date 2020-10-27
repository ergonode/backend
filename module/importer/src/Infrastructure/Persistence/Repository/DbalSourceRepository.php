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
use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Ergonode\Importer\Domain\Repository\SourceRepositoryInterface;
use Ergonode\Importer\Infrastructure\Persistence\Repository\Factory\DbalSourceFactory;
use Ergonode\Importer\Infrastructure\Persistence\Repository\Mapper\DbalSourceMapper;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;

class DbalSourceRepository implements SourceRepositoryInterface
{
    private const TABLE = 'importer.source';
    private const FIELDS = [
        'id',
        'type',
        'class',
        'configuration',
    ];

    private Connection $connection;

    private DbalSourceFactory $factory;

    private DbalSourceMapper $mapper;

    public function __construct(Connection $connection, DbalSourceFactory $factory, DbalSourceMapper $mapper)
    {
        $this->connection = $connection;
        $this->factory = $factory;
        $this->mapper = $mapper;
    }

    /**
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

    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(self::FIELDS)
            ->from(self::TABLE);
    }
}
