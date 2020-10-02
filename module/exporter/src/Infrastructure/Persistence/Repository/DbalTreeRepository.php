<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Infrastructure\Persistence\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Exporter\Domain\Entity\Catalog\ExportTree;
use Ergonode\Exporter\Domain\Repository\TreeRepositoryInterface;
use JMS\Serializer\SerializerInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class DbalTreeRepository implements TreeRepositoryInterface
{
    private const TABLE = 'exporter.tree';
    private const FIELDS = [
        'id',
        'data',
    ];

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @param Connection          $connection
     * @param SerializerInterface $serializer
     */
    public function __construct(Connection $connection, SerializerInterface $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    /**
     * @param Uuid $id
     *
     * @return ExportTree|null
     */
    public function load(Uuid $id): ?ExportTree
    {
        $qb = $this->getQuery();
        $record = $qb->where($qb->expr()->eq('id', ':id'))
            ->setParameter(':id', $id->toString())
            ->execute()
            ->fetch();

        if ($record) {
            return $this->serializer->deserialize($record['data'], ExportTree::class, 'json');
        }

        return null;
    }

    /**
     * @param ExportTree $exportTree
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function save(ExportTree $exportTree): void
    {
        if ($this->exists($exportTree->getId())) {
            $this->update($exportTree);
        } else {
            $this->insert($exportTree);
        }
    }

    /**
     * @param Uuid $id
     *
     * @return bool
     */
    public function exists(Uuid $id): bool
    {
        $query = $this->connection->createQueryBuilder();
        $result = $query->select(1)
            ->from(self::TABLE)
            ->where($query->expr()->eq('id', ':id'))
            ->setParameter(':id', $id->toString())
            ->execute()
            ->rowCount();

        if ($result) {
            return true;
        }

        return false;
    }

    /**
     * @param ExportTree $exportTree
     *
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Exception\InvalidArgumentException
     */
    public function delete(ExportTree $exportTree): void
    {
        $this->connection->delete(
            self::TABLE,
            [
                'id' => $exportTree->getId()->toString(),
            ]
        );
    }

    /**
     * @param ExportTree $exportTree
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function update(ExportTree $exportTree): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'data' => $this->serializer->serialize($exportTree, 'json'),
            ],
            [
                'id' => $exportTree->getId()->toString(),
            ]
        );
    }

    /**
     * @param ExportTree $exportTree
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function insert(ExportTree $exportTree): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'id' => $exportTree->getId()->toString(),
                'data' => $this->serializer->serialize($exportTree, 'json'),
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
