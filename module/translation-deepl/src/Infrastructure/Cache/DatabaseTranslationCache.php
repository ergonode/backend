<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\TranslationDeepl\Infrastructure\Cache;

use Doctrine\DBAL\Connection;
use Ergonode\Core\Infrastructure\Cache\CacheInterface;
use Ramsey\Uuid\UuidInterface;

/**
 */
class DatabaseTranslationCache implements CacheInterface
{
    private const TABLE = 'translation_cache';
    private const TRANSLATION_FIELD = 'a.translation';
    private const ID_FIELD = 'a.id';

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * TranslationDeeplProviderDecorator constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * {@inheritDoc}
     */
    public function get(UuidInterface $key): ?string
    {
        $result = $this->getQuery([self::TRANSLATION_FIELD, self::ID_FIELD], $key);

        return $result ? (string) $result : null;
    }

    /**
     * {@inheritDoc}
     */
    public function set(UuidInterface $key, string $data): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'id' => $key,
                'translation' => $data,
            ]
        );
    }

    /**
     * {@inheritDoc}
     */
    public function has(UuidInterface $key): bool
    {
        $result = $this->getQuery([self::ID_FIELD], $key);

        return false !== $result;
    }

    /**
     * @param array         $fields
     * @param UuidInterface $key
     *
     * @return false|mixed
     */
    public function getQuery(array $fields, UuidInterface $key)
    {

        return $this->connection->createQueryBuilder()
            ->select($fields)
            ->from(self::TABLE, 'a')
            ->andWhere('a.id = :id')
            ->setParameter(':id', $key)
            ->execute()
            ->fetchColumn();
    }
}
