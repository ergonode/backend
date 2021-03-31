<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Account\Domain\Query\AccountQueryInterface;
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

    public function __construct(
        Connection $connection,
        FilesystemInterface $avatarStorage
    ) {
        $this->connection = $connection;
        $this->avatarStorage = $avatarStorage;
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

    public function getUsers(): array
    {
        $query = $this->getQuery();

        $data = $query
            ->join('a', 'roles', 'r', 'r.id = a.role_id')
            ->andWhere($query->expr()->eq('hidden', ':qb_hidden'))
            ->setParameter(':qb_hidden', false, \PDO::PARAM_BOOL)
            ->execute()
            ->fetchAll();
        $result = [];

        foreach ($data as $item) {
            $result[] = array_merge(
                $item,
                ['language_privileges_collection' => json_decode($item['language_privileges_collection'], true)]
            );
        }
        unset($data);

        return $result;
    }


    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(self::FIELDS)
            ->from(self::TABLE, 'a');
    }
}
