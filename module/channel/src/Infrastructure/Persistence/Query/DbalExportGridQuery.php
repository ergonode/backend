<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Channel\Domain\Query\ExportGridQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

class DbalExportGridQuery implements ExportGridQueryInterface
{
    private const TABLE = 'exporter.export';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getGridQuery(ChannelId $channelId, Language $language): QueryBuilder
    {
        $query = $this->connection->createQueryBuilder();

        return $query
            ->select('e.id, e.status, e.started_at, e.ended_at')
            ->from(self::TABLE, 'e')
            ->addSelect('e.channel_id')
            ->andWhere($query->expr()->eq('channel_id', ':channelId'))
            ->setParameter(':channelId', $channelId->getValue());
    }
}
