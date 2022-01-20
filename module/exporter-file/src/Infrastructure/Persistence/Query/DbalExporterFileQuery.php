<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;
use Ergonode\ExporterFile\Domain\Query\ExporterFileQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;

class DbalExporterFileQuery implements ExporterFileQueryInterface
{

    private const AUDIT_TABLE = 'audit';
    private const CHANNEL_TABLE = 'exporter.channel';
    private const SEGMENT_PRODUCT_TABLE = 'public.segment_product';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return array
     */
    public function getAllEditedProductsInChannel(SegmentId $segmentId, ?\DateTime $dateTime = null): array
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->select('sp.product_id')
            ->from(self::SEGMENT_PRODUCT_TABLE, 'sp')
            ->where($qb->expr()->eq('segment_id', ':segmentId'))
            ->andWhere($qb->expr()->eq('available', ':available'))
            ->setParameter(':available', true)
            ->setParameter(':segmentId', $segmentId->getValue());

        if ($dateTime) {
            $qb
                ->join('sp', self::AUDIT_TABLE, 'a', 'a.id = sp.product_id')
                ->andWhere($qb->expr()->gte('a.edited_at', ':editedAt'))
                ->setParameter(':editedAt', $dateTime, Types::DATETIMETZ_MUTABLE);
        }

        return $qb
            ->execute()
            ->fetchFirstColumn();
    }

    /**
     * {@inheritDoc}
     */
    public function findIdBySegmentId(SegmentId $segmentId): array
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->select('id')
            ->from(self::CHANNEL_TABLE)
            ->where('configuration ->> \'segment_id\' = :id')
            ->setParameter(':id', $segmentId->getValue());
        $result = $queryBuilder->execute()->fetchFirstColumn();

        foreach ($result as &$item) {
            $item = new ChannelId($item);
        }

        return $result;
    }
}
