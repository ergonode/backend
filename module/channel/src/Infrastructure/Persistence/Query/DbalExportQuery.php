<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Channel\Domain\Query\ExportQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Channel\Domain\ValueObject\ExportStatus;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;

class DbalExportQuery implements ExportQueryInterface
{
    private const TABLE = 'exporter.export';
    private const TABLE_CHANNEL = 'exporter.channel';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return array
     */
    public function getProfileInfo(Language $language): array
    {
        $query = $this->getQuery();

        return $query
            ->addSelect('ch.name')
            ->addSelect('(SELECT count(*) FROM exporter.export_line el WHERE el.export_id = e.id) as items')
            ->addSelect('(SELECT count(*) FROM exporter.export_line el WHERE el.export_id = e.id 
                                AND processed_at IS NOT NULL) as processed')
            ->addSelect('(SELECT count(*) FROM exporter.export_error el WHERE el.export_id = e.id) as errors')
            ->orderBy('started_at', 'DESC')
            ->join('e', self::TABLE_CHANNEL, 'ch', 'ch.id = e.channel_id')
            ->setMaxResults(10)
            ->execute()
            ->fetchAll();
    }

    /**
     * @return array
     */
    public function getInformation(ExportId $exportId): array
    {
        $query = $this->getQuery();

        return $query
            ->addSelect('(SELECT count(*) FROM exporter.export_line el WHERE el.export_id = e.id) as items')
            ->addSelect('(SELECT count(*) FROM exporter.export_line el WHERE el.export_id = e.id 
                                AND processed_at IS NOT NULL) as processed')
            ->addSelect('(SELECT count(*) FROM exporter.export_error el WHERE el.export_id = e.id) as errors')
            ->where($query->expr()->eq('id', ':exportId'))
            ->setParameter(':exportId', $exportId->getValue())
            ->execute()
            ->fetch();
    }

    /**
     * @throws \Exception
     */
    public function findLastExport(ChannelId $channelId): ?\DateTime
    {
        $qb = $this->getQuery();
        $result = $qb
            ->andWhere($qb->expr()->eq('e.channel_id', ':channelId'))
            ->andWhere($qb->expr()->eq('e.status', ':status'))
            ->setParameter(':status', ExportStatus::ENDED)
            ->setParameter(':channelId', $channelId->getValue())
            ->orderBy('e.ended_at', 'DESC')
            ->setMaxResults(1)
            ->execute()
            ->fetch();

        if ($result) {
            return new \DateTime($result['ended_at']);
        }

        return null;
    }

    /**
     * @return ExportId[]
     */
    public function getExportIdsByChannelId(ChannelId $channelId): array
    {
        $qb = $this->connection->createQueryBuilder();

        $result = $qb->select('e.id')
            ->from(self::TABLE, 'e')
            ->where($qb->expr()->eq('e.channel_id', ':channelId'))
            ->setParameter(':channelId', $channelId->getValue())
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);

        if (false === $result) {
            $result = [];
        }

        foreach ($result as &$item) {
            $item = new ExportId($item);
        }

        return $result;
    }

    public function getChannelTypeByExportId(ExportId $exportId): ?string
    {
        $qb = $this->connection->createQueryBuilder();

        $result = $qb->select('ch.type')
            ->join('e', self::TABLE_CHANNEL, 'ch', 'ch.id = e.channel_id')
            ->where($qb->expr()->eq('e.id', ':exportId'))
            ->setParameter(':exportId', $exportId->getValue())
            ->from(self::TABLE, 'e')
            ->execute()
            ->fetch();

        if ($result) {
            return $result['type'];
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function findActiveExport(ChannelId $channelId): array
    {
        $qb = $this->getQuery();
        $result = $qb->select('e.id')
            ->where($qb->expr()->eq('e.channel_id', ':channelId'))
            ->setParameter(':channelId', $channelId->getValue())
            ->andWhere($qb->expr()->isNull('e.ended_at'))
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);

        foreach ($result as &$item) {
            $item = new ExportId($item);
        }

        return $result;
    }

    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('e.id, e.status, e.started_at, e.ended_at')
            ->from(self::TABLE, 'e');
    }
}
