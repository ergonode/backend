<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Account\Domain\Query\ProfileQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use League\Flysystem\FilesystemInterface;

class DbalProfileQuery implements ProfileQueryInterface
{
    private const TABLE = 'users';

    private Connection $connection;

    private FilesystemInterface $avatarStorage;

    public function __construct(Connection $connection, FilesystemInterface $avatarStorage)
    {
        $this->connection = $connection;
        $this->avatarStorage = $avatarStorage;
    }

    /**
     * {@inheritDoc}
     */
    public function getProfile(UserId $userId): array
    {
        $qb = $this->getQuery();
        $result = $qb->andWhere($qb->expr()->eq('u.id', ':userId'))
            ->setParameter('userId', $userId->getValue())
            ->execute()
            ->fetch();

        $filename = sprintf('%s.%s', $result['id'], 'png');
        $result['avatar_filename'] = $this->avatarStorage->has($filename) ? $filename : null;

        if (null !== $result['privileges']) {
            $result['privileges'] = json_decode($result['privileges'], true);
        }
        if (null !== $result['language_privileges']) {
            $result['language_privileges'] = json_decode($result['language_privileges'], true);
        }

        return $result;
    }

    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(
                'u.id,
                 u.first_name,
                 u.last_name, 
                 u.username AS email,
                 u.language,
                 u.language_privileges_collection AS language_privileges,
                 r.name AS role, 
                 r.privileges'
            )
            ->from(self::TABLE, 'u')
            ->join('u', 'roles', 'r', 'r.id = u.role_id');
    }
}
