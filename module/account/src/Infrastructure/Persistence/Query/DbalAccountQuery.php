<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Account\Domain\Query\AccountQueryInterface;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\Factory\DbalDataSetFactory;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use League\Flysystem\FilesystemInterface;

class DbalAccountQuery implements AccountQueryInterface
{
    private const TABLE = 'users';
    private const FIELDS = [
        'a.id',
        'a.first_name',
        'a.last_name',
        'a.username AS email',
        'a.language',
        'a.role_id',
        'a.is_active',
        'a.language_privileges_collection',
    ];

    private Connection $connection;

    private FilesystemInterface $avatarStorage;

    private DbalDataSetFactory $dataSetFactory;

    public function __construct(
        Connection $connection,
        FilesystemInterface $avatarStorage,
        DbalDataSetFactory $dataSetFactory
    ) {
        $this->connection = $connection;
        $this->avatarStorage = $avatarStorage;
        $this->dataSetFactory = $dataSetFactory;
    }

    public function getDataSet(): DataSetInterface
    {
        $query = $this->getQuery();
        $query->join('a', 'roles', 'r', 'r.id = a.role_id')
            ->andWhere($query->expr()->eq('hidden', ':hidden'));

        $result = $this->connection->createQueryBuilder();
        $result->select('*');
        $result->from(sprintf('(%s)', $query->getSQL()), 't');
        $result->setParameter(':hidden', false, \PDO::PARAM_BOOL);

        return $this->dataSetFactory->create($result);
    }

    /**
     * {@inheritDoc}
     */
    public function getUser(UserId $userId): ?array
    {
        $qb = $this->getQuery();

        $result = $qb
            ->andWhere($qb->expr()->eq('a.id', ':id'))
            ->setParameter(':id', $userId->getValue())
            ->execute()
            ->fetch();

        if ($result) {
            $filename = sprintf('%s.%s', $result['id'], 'png');
            $result['avatar_filename'] = $this->avatarStorage->has($filename) ? $filename : null;

            $result['language_privileges_collection'] = json_decode($result['language_privileges_collection'], true);

            return $result;
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function findUserIdByRoleId(RoleId $roleId): array
    {
        $queryBuilder = $this->connection->createQueryBuilder()
            ->select('id')
            ->from(self::TABLE)
            ->where('role_id = :role')
            ->setParameter('role', $roleId->getValue());
        $result = $queryBuilder->execute()->fetchAll(\PDO::FETCH_COLUMN);

        if (false === $result) {
            $result = [];
        }

        foreach ($result as &$item) {
            $item = new UserId($item);
        }

        return $result;
    }

    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(self::FIELDS)
            ->from(self::TABLE, 'a');
    }
}
