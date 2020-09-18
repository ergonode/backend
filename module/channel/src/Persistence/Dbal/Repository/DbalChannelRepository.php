<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Persistence\Dbal\Repository;

use Doctrine\DBAL\Types\Types;
use Ergonode\Channel\Domain\Entity\AbstractChannel;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Doctrine\DBAL\Connection;
use Ergonode\Channel\Persistence\Dbal\Repository\Factory\ChannelFactory;
use Ergonode\Channel\Persistence\Dbal\Repository\Mapper\ChannelMapper;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Exception\InvalidArgumentException;

/**
 */
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

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var ChannelFactory
     */
    private ChannelFactory $factory;

    /**
     * @var ChannelMapper
     */
    private ChannelMapper $mapper;

    /**
     * @param Connection     $connection
     * @param ChannelFactory $factory
     * @param ChannelMapper  $mapper
     */
    public function __construct(Connection $connection, ChannelFactory $factory, ChannelMapper $mapper)
    {
        $this->connection = $connection;
        $this->factory = $factory;
        $this->mapper = $mapper;
    }

    /**
     * @param ChannelId $id
     *
     * @return AbstractChannel|null
     */
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
     * @param AbstractChannel $channel
     *
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

    /**
     * @param ChannelId $id
     *
     * @return bool
     */
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
     * @param AbstractChannel $channel
     *
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
     * @param AbstractChannel $channel
     *
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
     * @param AbstractChannel $channel
     *
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
