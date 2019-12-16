<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Channel\Domain\Entity\ChannelId;
use Ergonode\Channel\Domain\Query\ChannelQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;

/**
 */
class DbalChannelQuery implements ChannelQueryInterface
{
    private const TABLE_VALUE = 'exporter.channel';

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        $qb = $this->getQuery();

        $results = $qb
            ->execute()
            ->fetchAll();

        if (false !== $results) {
            return $results;
        }

        return [];
    }

    /**
     * @param Language $language
     *
     * @return DataSetInterface
     */
    public function getDataSet(Language $language): DataSetInterface
    {
        return new DbalDataSet($this->getQuery());
    }

    /**
     * @param ChannelId $channelId
     *
     * @return array|null
     */
    public function findOneById(ChannelId $channelId): ?array
    {
        $qb = $this->getQuery();
        $result = $qb->where($qb->expr()->eq('id', ':id'))
            ->setParameter(':id', $channelId->getValue())
            ->execute()
            ->fetch(\PDO::FETCH_ASSOC);

        if ($result) {
            return $result;
        }

        return null;
    }

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('ch.id, ch.name')
            ->from(self::TABLE_VALUE, 'ch');
    }
}
