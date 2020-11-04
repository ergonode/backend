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
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Core\Infrastructure\Model\Relationship;

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
    public function addEntry(BatchActionId $id, AggregateId $resourceId): void
    {
        $this->connection->insert(
            self::TABLE_ENTRY,
            [
                'batch_action_id' => $id->getValue(),
                'resource_id' => $resourceId->getValue(),
            ],
        );
    }

    /**
     * @throws DBALException
     */
    public function markEntryAsSuccess(BatchActionId $id, AggregateId $resourceId): void
    {
        $this->updateEntry($id, $resourceId, true);
    }

    /**
     * @throws DBALException
     * @throws \JsonException
     */
    public function markEntryAsUnsuccess(BatchActionId $id, AggregateId $resourceId, Relationship $relationship): void
    {
        $message = [];
        foreach ($relationship as $group) {
            $message[] = [
                'message' => $group->getMessage(),
                '{relations}' => $group->getRelations(),
            ];
        }

        $this->updateEntry($id, $resourceId, false, json_encode($message, JSON_THROW_ON_ERROR));
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
     * @throws \JsonException
     */
    private function updateEntry(
        BatchActionId $id,
        AggregateId $resourceId,
        bool $success,
        string $message = null
    ): void {
        $json = null;
        if ($message) {
            $json = json_encode(['message' => $message, 'parameters' => []], JSON_THROW_ON_ERROR);
        }

        $this->connection->update(
            self::TABLE_ENTRY,
            [
                'success' => $success,
                'processed_at' => new \DateTime(),
                'fail_reason' => $json,
            ],
            [
                'batch_action_id' => $id->getValue(),
                'resource_id' => $resourceId->getValue(),
            ],
            [
                'processed_at' => Types::DATETIMETZ_MUTABLE,
                'success' => Types::BOOLEAN,
            ]
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
