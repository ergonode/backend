<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Workflow\Domain\Query\StatusGridQueryInterface;

class DbalStatusGridQuery implements StatusGridQueryInterface
{
    private const STATUS_TABLE = 'public.status';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getGridQuery(Language $language): QueryBuilder
    {
        $query = $this->getQuery($language);
        $query->addSelect(
            '(SELECT CASE WHEN count(*) > 0 THEN true ELSE false END FROM workflow w WHERE '.
            ' w.default_status = a.id AND w.code =\'default\')::BOOLEAN AS is_default '
        );
        $query->orderBy('index', 'ASC');

        return $query;
    }

    private function getQuery(Language $language): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(sprintf(
                'id, code, id AS status, color, name->>\'%s\' as name, description->>\'%s\' as description',
                $language->getCode(),
                $language->getCode()
            ))
            ->from(self::STATUS_TABLE, 'a');
    }
}
