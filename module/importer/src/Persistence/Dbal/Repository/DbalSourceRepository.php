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
use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Ergonode\Importer\Domain\Entity\Source\SourceId;
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
     * @param AbstractSource $Source
     *
     * @throws DBALException
     */
    public function save(AbstractSource $Source): void
    {
        if ($this->exists($Source->getId())) {
            $this->update($Source);
        } else {
            $this->insert($Source);
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
     * @param AbstractSource $Source
     *
     * @throws DBALException
     * @throws InvalidArgumentException
     */
    public function remove(AbstractSource $Source): void
    {
        $this->connection->delete(
            self::TABLE,
            [
                'id' => $Source->getId()->getValue(),
            ]
        );
    }

    /**
     * @param AbstractSource $Source
     *
     * @throws DBALException
     */
    private function update(AbstractSource $Source): void
    {
        $SourceArray = $this->mapper->map($Source);
        $SourceArray['updated_at'] = date('Y-m-d H:i:s');

        $this->connection->update(
            self::TABLE,
            $SourceArray,
            [
                'id' => $Source->getId()->getValue(),
            ]
        );
    }

    /**
     * @param AbstractSource $Source
     *
     * @throws DBALException
     */
    private function insert(AbstractSource $Source): void
    {
        $SourceArray = $this->mapper->map($Source);
        $SourceArray['created_at'] = date('Y-m-d H:i:s');
        $SourceArray['updated_at'] = date('Y-m-d H:i:s');

        $this->connection->insert(
            self::TABLE,
            $SourceArray
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
