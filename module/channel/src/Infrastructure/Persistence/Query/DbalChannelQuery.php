<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Channel\Domain\Query\ChannelQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;
use Ergonode\Grid\Filter\FilterBuilderProvider;

class DbalChannelQuery implements ChannelQueryInterface
{
    private const TABLE_VALUE = 'exporter.channel';

    private Connection $connection;

    private FilterBuilderProvider $filterBuilderProvider;

    public function __construct(Connection $connection, FilterBuilderProvider $filterBuilderProvider)
    {
        $this->connection = $connection;
        $this->filterBuilderProvider = $filterBuilderProvider;
    }

    public function getDataSet(Language $language): DataSetInterface
    {
        return new DbalDataSet($this->getQuery(), $this->filterBuilderProvider);
    }

    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('ch.id, ch.name, ch.type')
            ->from(self::TABLE_VALUE, 'ch');
    }
}
