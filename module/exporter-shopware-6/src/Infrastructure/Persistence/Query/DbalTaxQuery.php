<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;
use Ergonode\ExporterShopware6\Domain\Query\TaxQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

class DbalTaxQuery implements TaxQueryInterface
{
    private const TABLE = 'exporter.shopware6_tax';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function cleanData(ChannelId $channelId, \DateTimeImmutable $dateTime): void
    {
        $query = $this->connection->createQueryBuilder();
        $query->delete(self::TABLE, 't')
            ->where($query->expr()->eq('t.channel_id', ':channelId'))
            ->setParameter(':channelId', $channelId->getValue())
            ->andWhere($query->expr()->lt('t.update_at', ':updateAt'))
            ->setParameter(':updateAt', $dateTime, Types::DATETIMETZ_MUTABLE)
            ->execute();
    }
}
