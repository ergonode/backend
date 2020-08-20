<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Persistence\Dbal\Repository;

use Ergonode\Channel\Domain\Entity\Scheduler;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Exception\InvalidArgumentException;
use Ergonode\Channel\Persistence\Dbal\Repository\Factory\SchedulerFactory;
use Ergonode\Channel\Persistence\Dbal\Repository\Mapper\SchedulerMapper;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Channel\Domain\Repository\SchedulerRepositoryInterface;

/**
 */
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
     * @var SchedulerFactory
     */
    private SchedulerFactory $factory;

    /**
     * @var SchedulerMapper
     */
    private SchedulerMapper $mapper;

    /**
     * @param Connection       $connection
     * @param SchedulerFactory $factory
     * @param SchedulerMapper  $mapper
     */
    public function __construct(Connection $connection, SchedulerFactory $factory, SchedulerMapper $mapper)
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
