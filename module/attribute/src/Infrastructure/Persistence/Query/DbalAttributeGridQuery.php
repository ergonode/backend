<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Attribute\Domain\Query\AttributeGridQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;

class DbalAttributeGridQuery implements AttributeGridQueryInterface
{
    private const ATTRIBUTE_TABLE = 'attribute';
    private const FIELDS = [
        'a.id',
        'a.index',
        'a.code',
        'a.type',
        'a.system',
        'a.scope',
    ];

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getDataSetQuery(Language $language, bool $system = false): QueryBuilder
    {
        $query = $this->getQuery();
        $query->addSelect('
            (
                SELECT value 
                FROM value_translation t 
                WHERE t.value_id = a.label 
                AND t.language = :qb_language_code
            ) AS label');
        $query->where('a.system = :qb_is_system');
        $query->setParameter(':qb_language_code', $language->getCode());
        $query->setParameter(':qb_is_system', $system, \PDO::PARAM_BOOL);


        return $query;
    }

    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(self::FIELDS)
            ->addSelect(
                '(SELECT COALESCE(jsonb_agg(ag.id),\'[]\') FROM attribute_group_attribute aga  '.
                ' JOIN attribute_group ag ON aga.attribute_group_id = ag.id WHERE aga.attribute_id = a.id) AS groups'
            )
            ->from(self::ATTRIBUTE_TABLE, 'a');
    }
}
