<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Authentication\Infrastructure\Persistence;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;
use Ergonode\Authentication\Application\RefreshToken\Doctrine\RefreshTokenRepositoryInterface;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken;

class DbalRefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    private const TABLE = 'refresh_tokens';
    private const PROPERTIES = [
        'id' => 'id',
        'username' => 'username',
        'refreshToken' => 'refresh_token',
        'valid' => 'valid',
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
        $sql = 'SELECT id, username, refresh_token, valid FROM '.self::TABLE;
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
        throw new \BadMethodCallException('Not implemented');
    }

    /**
     * {@inheritdoc}
     */
    public function findOneBy(array $criteria): ?RefreshToken
    {
        $criteria = $this->mapCriteria($criteria);

        $qb = $this->connection->createQueryBuilder();
        $qb
            ->select('id, username, valid, refresh_token')
            ->from(self::TABLE, 'rt')
            ->setMaxResults(1);

        foreach ($criteria as $column => $value) {
            $qb
                ->andWhere($qb->expr()->eq("rt.$column", ":$column"))
                ->setParameter($column, $value)
            ;
        }
        $result = $qb->execute()->fetch();

        if (empty($result)) {
            return null;
        }

        return $this->mapRefreshToken($result);
    }

    public function getClassName(): string
    {
        return RefreshToken::class;
    }

    public function insert(RefreshToken $token): void
    {
        $sql = '
            INSERT INTO '.self::TABLE.' (username, valid, refresh_token)
                VALUES (
                    :username,
                    :valid,
                    :refreshToken
                )
                RETURNING id
            ';
        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue('username', $token->getUsername());
        $stmt->bindValue('valid', $token->getValid(), Types::DATETIMETZ_MUTABLE);
        $stmt->bindValue('refreshToken', $token->getRefreshToken());
        $stmt->execute();

        $id = $stmt->fetchColumn();

        $this->setId($token, (int) $id);
    }

    public function delete(RefreshToken $token): void
    {
        $this->connection->delete(
            self::TABLE,
            [
                'id' => $token->getId(),
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function findInvalid(?\DateTimeInterface $dateTime = null): array
    {
        if (null === $dateTime) {
            $dateTime = new \DateTime();
        }
        $sql = 'SELECT id, username, valid, refresh_token FROM '.self::TABLE.' WHERE valid < :dateTime';
        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue('dateTime', $dateTime, Types::DATETIMETZ_MUTABLE);
        $stmt->execute();

        return array_map(
            fn (array $data) => $this->mapRefreshToken($data),
            $stmt->fetchAll(),
        );
    }

    private function mapCriteria(array $criteria): array
    {
        if ($invalid = array_diff_key($criteria, self::PROPERTIES)) {
            throw new \UnexpectedValueException(sprintf(
                'Invalid criteria given. Keys do not exist %s',
                implode(',', array_keys($invalid)),
            ));
        }

        $new = [];
        foreach ($criteria as $property => $criterion) {
            $new[self::PROPERTIES[$property]] = $criterion;
        }

        return $new;
    }

    private function mapRefreshToken(array $data): RefreshToken
    {
        $token = new RefreshToken();
        $token
            ->setRefreshToken($data['refresh_token'])
            ->setUsername($data['username'])
            ->setValid(\DateTime::createFromFormat('Y-m-d H:i:sO', $data['valid']));
        $this->setId($token, (int) $data['id']);

        return $token;
    }

    private function setId(RefreshToken $refreshToken, int $id): void
    {
        $idProperty = $this->reflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($refreshToken, $id);
        $idProperty->setAccessible(false);
    }
}
