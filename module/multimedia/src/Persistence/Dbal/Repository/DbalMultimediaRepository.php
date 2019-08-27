<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Persistence\Dbal\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\Multimedia\Domain\Entity\MultimediaId;
use Ergonode\Multimedia\Domain\Repository\MultimediaRepositoryInterface;
use Ergonode\Multimedia\Persistence\Dbal\Repository\Factory\MultimediaFactory;
use Ergonode\Multimedia\Persistence\Dbal\Repository\Mapper\MultimediaMapper;

/**
 */
class DbalMultimediaRepository implements MultimediaRepositoryInterface
{
    private const TABLE = 'multimedia';
    private const FIELDS = [
        'id',
        'name',
        'extension',
        'size',
        'mime',
        'extension',
    ];

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var MultimediaMapper;
     */
    private $mapper;

    /**
     * @var MultimediaFactory
     */
    private $factory;

    /**
     * @param Connection        $connection
     * @param MultimediaMapper  $mapper
     * @param MultimediaFactory $factory
     */
    public function __construct(Connection $connection, MultimediaMapper $mapper, MultimediaFactory $factory)
    {
        $this->connection = $connection;
        $this->mapper = $mapper;
        $this->factory = $factory;
    }

    /**
     * @param MultimediaId $id
     *
     * @return Multimedia|null
     *
     * @throws \ReflectionException
     */
    public function load(MultimediaId $id): ?Multimedia
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
     * @param Multimedia $multimedia
     *
     * @throws DBALException
     */
    public function save(Multimedia $multimedia): void
    {
        if ($this->exists($multimedia->getId())) {
            $this->update($multimedia);
        } else {
            $this->insert($multimedia);
        }
    }

    /**
     * @param MultimediaId $id
     *
     * @return bool
     */
    public function exists(MultimediaId $id): bool
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
     * @param MultimediaId $id
     *
     * @throws DBALException
     * @throws \Doctrine\DBAL\Exception\InvalidArgumentException
     */
    public function remove(MultimediaId $id): void
    {
        $this->connection->delete(
            self::TABLE,
            [
                'id' => $id->getValue(),
            ]
        );
    }

    /**
     * @param Multimedia $multimedia
     *
     * @throws DBALException
     */
    private function update(Multimedia $multimedia): void
    {
        $multimediaArray = $this->mapper->map($multimedia);

        $this->connection->update(
            self::TABLE,
            $multimediaArray,
            [
                'id' => $multimedia->getId()->getValue(),
            ]
        );
    }

    /**
     * @param Multimedia $multimedia
     *
     * @throws DBALException
     */
    private function insert(Multimedia $multimedia): void
    {
        $multimediaArray = $this->mapper->map($multimedia);

        $this->connection->insert(
            self::TABLE,
            $multimediaArray
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
