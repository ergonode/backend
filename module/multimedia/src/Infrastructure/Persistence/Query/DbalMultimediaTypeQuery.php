<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\Multimedia\Domain\Query\MultimediaTypeQueryInterface;

class DbalMultimediaTypeQuery implements MultimediaTypeQueryInterface
{
    private const TABLE = 'multimedia';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findMultimediaType(MultimediaId $id): ?string
    {
        $qb = $this->connection->createQueryBuilder();

        $result = $qb
            ->select('(left(m.mime, strpos(m.mime, \'/\')-1)) AS type')
            ->from(self::TABLE, 'm')
            ->where($qb->expr()->eq('id', ':id'))
            ->setParameter('id', $id->getValue())
            ->execute()
            ->fetchOne();

        return $result ?: null;
    }
}
