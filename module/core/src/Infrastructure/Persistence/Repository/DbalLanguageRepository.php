<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Persistence\Repository;

use Doctrine\DBAL\Connection;
use Ergonode\Core\Domain\Repository\LanguageRepositoryInterface;
use Ergonode\Core\Domain\ValueObject\Language;

class DbalLanguageRepository implements LanguageRepositoryInterface
{
    private const TABLE = 'language';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    public function save(Language $language, bool $active): void
    {
        if ($this->exists($language)) {
            $this->connection->update(
                self::TABLE,
                [
                    'active' => $active,
                ],
                [
                    'iso' => $language->getCode(),
                ],
                [
                    'active' => \PDO::PARAM_BOOL,
                ]
            );
        }
    }

    public function exists(Language $language): bool
    {
        $query = $this->connection->createQueryBuilder();
        $result = $query->select(1)
            ->from(self::TABLE)
            ->where($query->expr()->eq('iso', ':iso'))
            ->setParameter(':iso', $language)
            ->execute()
            ->rowCount();

        if ($result) {
            return true;
        }

        return false;
    }
}
