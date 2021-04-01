<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Ergonode\Importer\Domain\Query\SourceQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;

class DbalSourceQuery implements SourceQueryInterface
{
    private const TABLE = 'importer.source';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return SourceId[]
     */
    public function findSourceIdsByType(string $type): array
    {
        $qb = $this->connection->createQueryBuilder();

        $data = $qb
            ->select('id')
            ->from(self::TABLE)
            ->where($qb->expr()->eq('type', ':type'))
            ->setParameter(':type', $type)
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);

        $result = [];
        foreach ($data as $sourceId) {
            $result[] = new SourceId($sourceId);
        }

        return $result;
    }
}
