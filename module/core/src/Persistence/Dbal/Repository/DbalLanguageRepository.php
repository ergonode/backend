<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Persistence\Dbal\Repository;

use Doctrine\DBAL\Connection;
use Ergonode\Core\Domain\Repository\LanguageRepositoryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Persistence\Dbal\Query\DbalLanguageQuery;

/**
 */
class DbalLanguageRepository implements LanguageRepositoryInterface
{
    private const TABLE = 'language';

    /**
     * @var Connection
     */
    private Connection $connection;
    /**
     * @var DbalLanguageQuery
     */
    private DbalLanguageQuery $query;

    /**
     * @param Connection        $connection
     * @param DbalLanguageQuery $query
     */
    public function __construct(Connection $connection, DbalLanguageQuery $query)
    {
        $this->connection = $connection;
        $this->query = $query;
    }

    /**
     * @param Language $language
     * @param bool     $active
     *
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

    /**
     * @param Language $language
     *
     * @return bool
     */
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
