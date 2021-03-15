<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Ergonode\Designer\Domain\Query\TemplateGroupGridQueryInterface;
use Doctrine\DBAL\Query\QueryBuilder;

class DbalTemplateGroupGridQuery implements TemplateGroupGridQueryInterface
{
    private const TABLE = 'designer.template_group';
    private const FIELDS = [
        'id',
        'name',
        'custom',
    ];

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getGridQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(self::FIELDS)
            ->from(self::TABLE);
    }
}
