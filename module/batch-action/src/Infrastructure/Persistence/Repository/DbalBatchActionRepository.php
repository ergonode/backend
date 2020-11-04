<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Infrastructure\Persistence\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Types;
use Ergonode\BatchAction\Domain\Repository\BatchActionRepositoryInterface;
use Ergonode\BatchAction\Infrastructure\Persistence\Repository\Mapper\DbalBatchActionMapper;
use Ergonode\BatchAction\Domain\Entity\BatchActionId;
use Ergonode\BatchAction\Domain\Entity\BatchAction;
use Ergonode\SharedKernel\Domain\AbstractId;

class DbalBatchActionRepository implements BatchActionRepositoryInterface
{
    private const TABLE = 'batch_action';
    private const TABLE_ENTRY = 'batch_action_entry';
    private const FIELDS = [
        'id',
        'resource_type',
        'action',
    ];

    private Connection $connection;

    private DbalBatchActionMapper $mapper;

    public function __construct(Connection $connection, DbalBatchActionMapper $mapper)
    {
        $this->connection = $connection;
        $this->mapper = $mapper;
    }

    /**
     * @throws \ReflectionException
     */
    public function load(BatchActionId $id): ?BatchAction
    {
        $qb = $this->getQuery();
        $record = $qb->where($qb->expr()->eq('id', ':id'))
            ->setParameter(':id', $id->getValue())
            ->execute()
            ->fetch();

        if ($record) {
            return $this->mapper->create($record);
        }

        return null;
    }

    /**
     * @throws DBALException
     */
    public function save(BatchAction $bachAction): void
    {
        if ($this->exists($bachAction->getId())) {
            $this->update($bachAction);
        } else {
            $this->insert($bachAction);
        }
    }

    public function exists(BatchActionId $id): bool
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
     */
    public function addEntry(BatchActionId $id, AbstractId $entryId): void
    {
        $this->connection->insert(
            self::TABLE_ENTRY,
            [
                'batch_action_id' => $id->getValue(),
                'resource_id' => $entryId->getValue(),
            ],
        );
    }

    /**
     * @throws DBALException
     */
    private function update(BatchAction $bachAction): void
    {
        $bachActionArray = $this->mapper->map($bachAction);

        $this->connection->update(
            self::TABLE,
            $bachActionArray,
            [
                'id' => $bachAction->getId()->getValue(),
            ],
        );
    }

    /**
     * @throws DBALException
     */
    private function insert(BatchAction $bachAction): void
    {
        $bachActionArray = $this->mapper->map($bachAction);
        $bachActionArray['created_at'] = new \DateTime();

        $this->connection->insert(
            self::TABLE,
            $bachActionArray,
            [
                'created_at' => Types::DATETIMETZ_MUTABLE,
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
