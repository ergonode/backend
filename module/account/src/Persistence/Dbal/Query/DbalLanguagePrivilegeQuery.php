<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Account\Domain\Query\LanguagePrivilegeQueryInterface;

/**
 */
class DbalLanguagePrivilegeQuery implements LanguagePrivilegeQueryInterface
{
    public const LANGUAGE_PRIVILEGES_TABLE = 'language_privileges';
    public const FIELDS = [
        'l.id',
        'l.code',
        'l.language',
    ];

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return array
     */
    public function getLanguagePrivileges(): array
    {
        $qb = $this->getQuery();

        return $qb->execute()->fetchAll();
    }

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(self::FIELDS)
            ->from(self::LANGUAGE_PRIVILEGES_TABLE, 'l');
    }
}
