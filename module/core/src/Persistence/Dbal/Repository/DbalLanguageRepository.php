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
    private const ALL_FIELDS = [
        'id',
        'iso AS code',
        'name',
        'active',
    ];
    private const CODE_FIELD = [
        'iso AS code',
    ];

    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var DbalLanguageQuery
     */
    private $query;

    /**
     * DbalLanguageRepository constructor.
     *
     * @param Connection        $connection
     * @param DbalLanguageQuery $query
     */
    public function __construct(Connection $connection, DbalLanguageQuery $query)
    {
        $this->connection = $connection;
        $this->query = $query;
    }

    /**
     * @param array $codes
     *
     * @return array
     */
    public function load(array $codes): array
    {
        return $this->query->getLanguages($codes);
    }

    /**
     * @param Language $languageCode
     * @param bool     $active
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function save(Language $languageCode, bool $active): void
    {
        if ($this->exists($languageCode)) {
            $this->connection->update(
                self::TABLE,
                [
                    'active' => $active,
                ],
                [
                    'iso' => $languageCode->getCode(),
                ],
                [
                    'active' => \PDO::PARAM_BOOL,
                ]
            );
        }
    }

    /**
     * @param Language $languageCode
     *
     * @return bool
     */
    public function exists(Language $languageCode): bool
    {
        $query = $this->connection->createQueryBuilder();
        $result = $query->select(1)
            ->from(self::TABLE)
            ->where($query->expr()->eq('iso', ':iso'))
            ->setParameter(':iso', $languageCode)
            ->execute()
            ->rowCount();

        if ($result) {
            return true;
        }

        return false;
    }

}
