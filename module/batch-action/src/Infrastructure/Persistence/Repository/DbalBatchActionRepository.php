<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
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
use Ergonode\SharedKernel\Application\Serializer\SerializerInterface;
use Ergonode\SharedKernel\Domain\AggregateId;
use Webmozart\Assert\Assert;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionMessage;
use Symfony\Component\Security\Core\Security;
use Ergonode\Account\Domain\Entity\User;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;

class DbalBatchActionRepository implements BatchActionRepositoryInterface
{
    private const TABLE = 'batch_action';
    private const TABLE_ENTRY = 'batch_action_entry';
    private const FIELDS = [
        'id',
        'type',
        'payload',
        'status',
        'auto_end_on_errors',
    ];

    private Connection $connection;

    private DbalBatchActionMapper $mapper;

    private SerializerInterface $serializer;

    private Security $security;

    public function __construct(
        Connection $connection,
        DbalBatchActionMapper $mapper,
        SerializerInterface $serializer,
        Security $security
    ) {
        $this->connection = $connection;
        $this->mapper = $mapper;
        $this->serializer = $serializer;
        $this->security = $security;
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
     * @param BatchActionMessage[] $messages
     *
     * @throws DBALException
     * @throws \JsonException
     */
    public function markEntry(BatchActionId $id, AggregateId $resourceId, array $messages): void
    {
        Assert::allIsInstanceOf($messages, BatchActionMessage::class);

        $success = true;
        $json = null;
        if (!empty($messages)) {
            $json = $this->serializer->serialize($messages);
            $success = false;
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

    public function endBatchAction(BatchActionId $id): void
    {
        $this->connection->update(
            'batch_action',
            [
                'processed_at' => new \DateTime(),
            ],
            [
                'id' => $id->getValue(),
            ],
            [
                'processed_at' => Types::DATETIMETZ_MUTABLE,
            ]
        );
    }

    public function reprocess(BatchAction $batchAction): void
    {
        $bachActionArray = $this->mapper->map($batchAction);
        $bachActionArray['processed_at'] = null;

        $this->connection->update(
            self::TABLE,
            $bachActionArray,
            [
                'id' => $batchAction->getId()->getValue(),
            ],
            [
                'auto_end_on_errors' => \PDO::PARAM_BOOL,
            ]
        );

        $this->connection->update(
            self::TABLE_ENTRY,
            [
                'success' => null,
                'fail_reason' => null,
                'processed_at' => null,
            ],
            [
                'batch_action_id' => $batchAction->getId()->getValue(),
                'success' => false,
            ],
            [
                'success' => \PDO::PARAM_BOOL,
            ]
        );
    }

    /**
     * @throws DBALException
     */
    private function update(BatchAction $batchAction): void
    {
        $bachActionArray = $this->mapper->map($batchAction);

        $this->connection->update(
            self::TABLE,
            $bachActionArray,
            [
                'id' => $batchAction->getId()->getValue(),
            ],
            [
                'auto_end_on_errors' => \PDO::PARAM_BOOL,
            ]
        );
    }

    /**
     * @throws DBALException
     */
    private function insert(BatchAction $batchAction): void
    {
        $bachActionArray = $this->mapper->map($batchAction);
        $bachActionArray['created_at'] = new \DateTime();
        $bachActionArray['created_by'] = $this->getUserId() ? $this->getUserId()->getValue() : null;

        $this->connection->insert(
            self::TABLE,
            $bachActionArray,
            [
                'created_at' => Types::DATETIMETZ_MUTABLE,
                'auto_end_on_errors' => \PDO::PARAM_BOOL,
            ],
        );
    }

    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(self::FIELDS)
            ->from(self::TABLE);
    }

    private function getUserId(): ?UserId
    {
        $user = $this->security->getUser();
        if ($user instanceof User) {
            return $user->getId();
        }

        return null;
    }
}
