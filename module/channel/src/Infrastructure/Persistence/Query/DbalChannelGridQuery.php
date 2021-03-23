<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Channel\Domain\Query\ChannelGridQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;

class DbalChannelGridQuery implements ChannelGridQueryInterface
{
    private const CHANNEL_TABLE = 'exporter.channel';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getGridQuery(Language $language): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('ch.id, ch.name, ch.type')
            ->from(self::CHANNEL_TABLE, 'ch');
    }
}
