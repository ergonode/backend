<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\TranslationDeepl\Infrastructure\Cache;

use Doctrine\DBAL\Connection;
use Ergonode\Core\Infrastructure\Cache\CacheInterface;
use Ergonode\TranslationDeepl\Infrastructure\Provider\TranslationDeeplProvider;
use Ramsey\Uuid\UuidInterface;

/**
 */
class DatabaseTranslationCache implements CacheInterface
{
    private const TABLE = 'translation_cache';
    private const FIELDS = [
        'a.translation',
    ];

    /**
     * @var Connection
     */
    private $connection;

    /**
     * TranslationDeeplProviderDecorator constructor.
     *
     * @param TranslationDeeplProvider $provider
     * @param Connection               $connection
     */
    public function __construct(TranslationDeeplProvider $provider, Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * {@inheritDoc}
     */
    public function get(UuidInterface $key)
    {
        return $this->connection->createQueryBuilder()
            ->select(self::FIELDS)
            ->from(self::TABLE, 'a')
            ->andWhere('a.id = :id')
            ->setParameter(':id', $key)
            ->execute()
            ->fetchColumn();
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
        $result = $this->connection->createQueryBuilder()
            ->select('*')
            ->from(self::TABLE, 'a')
            ->andWhere('a.id = :id')
            ->setParameter(':id', $key)
            ->execute()
            ->fetchColumn();

        return null !== $result;
    }
}
