<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Segment\Domain\Query\SegmentGridQueryInterface;

class DbalSegmentGridQuery implements SegmentGridQueryInterface
{
    private const TABLE = 'segment';
    private const FIELDS = [
        't.id',
        't.code',
    ];

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getGridQuery(Language $language): QueryBuilder
    {
        $query = $this->connection->createQueryBuilder();

        return $query->select(self::FIELDS)
            ->from(self::TABLE, 't')
            ->addSelect(sprintf('(name->>\'%s\') AS name', $language->getCode()))
            ->addSelect(sprintf('(description->>\'%s\') AS description', $language->getCode()))
            ->addSelect(
                '(SELECT count(*) FROM segment_product
            WHERE segment_id = t.id
            AND available = true AND calculated_at IS NOT NULL)
            AS products_count'
            )
            ->addSelect(
                '(SELECT
            CASE
                WHEN count(*) = 0 THEN \'new\'
                WHEN count(calculated_at) = count(*)  THEN \'calculated\'
                ELSE \'processed\'
            END
            FROM segment_product WHERE segment_id = t.id) as status'
            );
    }
}
