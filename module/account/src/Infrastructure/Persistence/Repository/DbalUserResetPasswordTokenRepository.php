<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Persistence\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Types;
use Ergonode\Account\Domain\Entity\UserResetPasswordToken;
use Ergonode\Account\Domain\Repository\UserResetPasswordTokenRepositoryInterface;
use Ergonode\Account\Domain\ValueObject\ResetToken;
use Ergonode\Account\Infrastructure\Persistence\Repository\Mapper\DbalUserResetPasswordTokenMapper;

class DbalUserResetPasswordTokenRepository implements UserResetPasswordTokenRepositoryInterface
{
    private const TABLE = 'users_token';
    private const FIELDS = [
        'user_id',
        'token',
        'expires_at',
        'consumed',
    ];

    private Connection $connection;

    private DbalUserResetPasswordTokenMapper $mapper;

    public function __construct(Connection $connection, DbalUserResetPasswordTokenMapper $mapper)
    {
        $this->connection = $connection;
        $this->mapper = $mapper;
    }

    public function load(ResetToken $token): ?UserResetPasswordToken
    {
        $query = $this->getQuery();
        $record = $query->where($query->expr()->eq('token', ':token'))
            ->setParameter(':token', $token->getValue())
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
    public function save(UserResetPasswordToken $userResetPasswordToken): void
    {
        if ($this->exists($userResetPasswordToken->getToken())) {
            $this->update($userResetPasswordToken);
        } else {
            $this->insert($userResetPasswordToken);
        }
    }

    public function exists(ResetToken $token): bool
    {
        $query = $this->connection->createQueryBuilder();
        $result = $query->select(1)
            ->from(self::TABLE)
            ->where($query->expr()->eq('token', ':token'))
            ->setParameter(':token', $token->getValue())
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
    private function update(UserResetPasswordToken $userResetPasswordToken): void
    {
        $userResetPasswordTokenArray = $this->mapper->map($userResetPasswordToken);

        $types = [
            'expires_at' => Types::DATETIMETZ_MUTABLE,
        ];
        if ($userResetPasswordToken->getConsumed()) {
            $types['consumed'] = Types::DATETIMETZ_MUTABLE;
        }

        $this->connection->update(
            self::TABLE,
            $userResetPasswordTokenArray,
            [
                'token' => $userResetPasswordToken->getToken()->getValue(),
            ],
            $types
        );
    }

    /**
     * @throws DBALException
     */
    private function insert(UserResetPasswordToken $userResetPasswordToken): void
    {
        $userResetPasswordTokenArray = $this->mapper->map($userResetPasswordToken);

        $types = [
            'expires_at' => Types::DATETIMETZ_MUTABLE,
        ];
        if ($userResetPasswordToken->getConsumed()) {
            $types['consumed'] = Types::DATETIMETZ_MUTABLE;
        }

        $this->connection->insert(
            self::TABLE,
            $userResetPasswordTokenArray,
            $types,
        );
    }

    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(self::FIELDS)
            ->from(self::TABLE);
    }
}
