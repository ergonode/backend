<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Infrastructure\Persistence\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Exception\InvalidArgumentException;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Types;
use Ergonode\Channel\Domain\Entity\Scheduler;
use Ergonode\Channel\Domain\Repository\SchedulerRepositoryInterface;
use Ergonode\Channel\Infrastructure\Persistence\Repository\Factory\DbalSchedulerFactory;
use Ergonode\Channel\Infrastructure\Persistence\Repository\Mapper\DbalSchedulerMapper;
use Ergonode\SharedKernel\Domain\AggregateId;

class DbalSchedulerRepository implements SchedulerRepositoryInterface
{
    private const TABLE = 'exporter.scheduler';
    private const FIELDS = [
        'id',
        'active',
        'start',
        'hour',
        'minute',
    ];

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var DbalSchedulerFactory
     */
    private DbalSchedulerFactory $factory;

    /**
     * @var DbalSchedulerMapper
     */
    private DbalSchedulerMapper $mapper;

    /**
     * @param Connection           $connection
     * @param DbalSchedulerFactory $factory
     * @param DbalSchedulerMapper  $mapper
     */
    public function __construct(Connection $connection, DbalSchedulerFactory $factory, DbalSchedulerMapper $mapper)
    {
        $this->connection = $connection;
        $this->factory = $factory;
        $this->mapper = $mapper;
    }

    /**
     * @param AggregateId $id
     *
     * @return Scheduler|null
     */
    public function load(AggregateId $id): ?Scheduler
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
     * @param Scheduler $channel
     *
     * @throws DBALException
     */
    public function save(Scheduler $channel): void
    {
        if ($this->exists($channel->getId())) {
            $this->update($channel);
        } else {
            $this->insert($channel);
        }
    }

    /**
     * @param AggregateId $id
     *
     * @return bool
     */
    public function exists(AggregateId $id): bool
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
     * @param Scheduler $channel
     *
     * @throws DBALException
     * @throws InvalidArgumentException
     */
    public function delete(Scheduler $channel): void
    {
        $this->connection->delete(
            self::TABLE,
            [
                'id' => $channel->getId()->getValue(),
            ]
        );
    }


    /**
     * @param Scheduler $channel
     *
     * @throws DBALException
     */
    private function update(Scheduler $channel): void
    {
        $data = $this->mapper->map($channel);

        $this->connection->update(
            self::TABLE,
            $data,
            [
                'id' => $channel->getId()->getValue(),
            ],
            [
                'active' => \PDO::PARAM_BOOL,
                'start' => Types::DATETIMETZ_MUTABLE,
            ]
        );
    }

    /**
     * @param Scheduler $channel
     *
     * @throws DBALException
     */
    private function insert(Scheduler $channel): void
    {
        $data = $this->mapper->map($channel);

        $this->connection->insert(
            self::TABLE,
            $data,
            [
                'active' => \PDO::PARAM_BOOL,
                'start' => Types::DATETIMETZ_MUTABLE,
            ]
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
