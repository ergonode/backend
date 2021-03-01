<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Authentication\Infrastructure\Persistence;

use Doctrine\DBAL\Connection;
use Ergonode\Authentication\Application\RefreshToken\Doctrine\RefreshTokenRepositoryInterface;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken;

class DbalRefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    private const TABLE = 'refresh_tokens';
    private const FIELDS = [
        'id' => true,
        'username' => true,
        'refresh_token' => true,
        'valid' => true,
    ];

    private Connection $connection;
    private \ReflectionClass $reflection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->reflection = new \ReflectionClass(RefreshToken::class);
    }

    /**
     * {@inheritdoc}
     */
    public function find($id): ?RefreshToken
    {
        return $this->findOneBy(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     *
     * @return RefreshToken[]
     */
    public function findAll(): array
    {
        $sql = 'SELECT id, username, refresh_token, valid FROM ' . self::TABLE;
        $result = $this->connection->query($sql)->fetchAll();

        return array_map(
            fn(array $data) => $this->mapRefreshToken($data),
            $result,
        );
    }

    /**
     * {@inheritdoc}
     *
     * @return RefreshToken[]
     */
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array
    {
//        $this->validateCriteria($criteria);
        throw new \BadMethodCallException('Not implemented');
    }

    /**
     * {@inheritdoc}
     */
    public function findOneBy(array $criteria): ?RefreshToken
    {
        $this->validateCriteria($criteria);

        $qb = $this->connection->createQueryBuilder();
        $qb
            ->select('*')
            ->from(self::TABLE, 'rt')
            ->setMaxResults(1);

        foreach ($criteria as $column => $value) {
            $qb
                ->andWhere($qb->expr()->eq("rt.$column", ":$column"))
                ->setParameter($column, $value)
            ;
        }
        $result = $qb->execute()->fetchAll();

        return $this->mapRefreshToken($result);
    }

    public function getClassName(): string
    {
        return RefreshToken::class;
    }

    public function insert(RefreshToken $token)
    {
        // TODO: Implement insert() method.
    }

    public function delete(RefreshToken $token)
    {
        // TODO: Implement delete() method.
    }

    private function validateCriteria(array $criteria): void
    {
        if (!$invalid = array_diff_key($criteria, self::FIELDS)) {
            return;
        }

        throw new \UnexpectedValueException(
            'Invalid criteria given. Keys do not exist ' . array_keys($invalid),
        );
    }

    private function mapRefreshToken(array $data): RefreshToken
    {
        $token = new RefreshToken();
        $token
            ->setRefreshToken($data['refresh_token'])
            ->setUsername($data['username'])
            ->setValid($data['valid']);
        $id = $this->reflection->getProperty('id');
        $id->setAccessible(true);
        $id->setValue($token, $data['id']);

        return $token;
    }
}
