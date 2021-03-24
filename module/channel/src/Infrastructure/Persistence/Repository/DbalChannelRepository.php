<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Infrastructure\Persistence\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Exception\InvalidArgumentException;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Types;
use Ergonode\Channel\Domain\Entity\AbstractChannel;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\Channel\Infrastructure\Persistence\Repository\Factory\DbalChannelFactory;
use Ergonode\Channel\Infrastructure\Persistence\Repository\Mapper\DbalChannelMapper;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

class DbalChannelRepository implements ChannelRepositoryInterface
{
    private const TABLE = 'exporter.channel';
    private const FIELDS = [
        'id',
        'type',
        'class',
        'name',
        'configuration',
    ];

    private Connection $connection;

    private DbalChannelFactory $factory;

    private DbalChannelMapper $mapper;

    public function __construct(Connection $connection, DbalChannelFactory $factory, DbalChannelMapper $mapper)
    {
        $this->connection = $connection;
        $this->factory = $factory;
        $this->mapper = $mapper;
    }

    public function load(ChannelId $id): ?AbstractChannel
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
    public function save(AbstractChannel $channel): void
    {
        if ($this->exists($channel->getId())) {
            $this->update($channel);
        } else {
            $this->insert($channel);
        }
    }

    public function exists(ChannelId $id): bool
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
    public function delete(AbstractChannel $channel): void
    {
        $this->connection->delete(
            self::TABLE,
            [
                'id' => $channel->getId()->getValue(),
            ]
        );
    }


    /**
     * @throws DBALException
     */
    private function update(AbstractChannel $channel): void
    {
        $data = $this->mapper->map($channel);
        $data['updated_at'] = new \DateTime();

        $this->connection->update(
            self::TABLE,
            $data,
            [
                'id' => $channel->getId()->getValue(),
            ],
            [
                'updated_at' => Types::DATETIMETZ_MUTABLE,
            ],
        );
    }

    /**
     * @throws DBALException
     */
    private function insert(AbstractChannel $channel): void
    {
        $data = $this->mapper->map($channel);
        $data['created_at'] = $data['updated_at'] = new \DateTime();

        $this->connection->insert(
            self::TABLE,
            $data,
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
