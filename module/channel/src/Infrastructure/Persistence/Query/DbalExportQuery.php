<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Channel\Domain\Query\ExportQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Exporter\Domain\ValueObject\ExportStatus;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;

class DbalExportQuery implements ExportQueryInterface
{
    private const TABLE = 'exporter.export';
    private const TABLE_LINE = 'exporter.export_line';
    private const TABLE_CHANNEL = 'exporter.channel';

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param ChannelId $channelId
     * @param Language  $language
     *
     * @return DataSetInterface
     */
    public function getDataSet(ChannelId $channelId, Language $language): DataSetInterface
    {
        $query = $this->getQuery();
        $query->addSelect('e.channel_id');
        $query->andWhere($query->expr()->eq('channel_id', ':channelId'));

        $result = $this->connection->createQueryBuilder();
        $result->select('*');
        $result->from(sprintf('(%s)', $query->getSQL()), 't')
            ->setParameter(':channelId', $channelId->getValue());

        return new DbalDataSet($result);
    }

    /**
     * @param ExportId $exportId
     * @param Language $language
     *
     * @return DataSetInterface
     */
    public function getErrorDataSet(ExportId $exportId, Language $language): DataSetInterface
    {
        $query = $this->connection->createQueryBuilder();
        $query->select('object_id AS id, processed_at, message')
            ->from(self::TABLE_LINE)
            ->where($query->expr()->eq('export_id', ':exportId'));

        $result = $this->connection->createQueryBuilder();
        $result->select('*');
        $result->from(sprintf('(%s)', $query->getSQL()), 't')
            ->setParameter(':exportId', $exportId->getValue());

        return new DbalDataSet($result);
    }

    /**
     * @param Language $language
     *
     * @return array
     */
    public function getProfileInfo(Language $language): array
    {
        $query = $this->getQuery();

        return $query
            ->addSelect('ch.name, e.items')
            ->addSelect('(SELECT count(*) FROM exporter.export_line el WHERE el.export_id = e.id 
                                AND processed_at IS NOT NULL) as processed')
            ->addSelect('(SELECT count(*) FROM exporter.export_line el WHERE el.export_id = e.id 
                                AND processed_at IS NOT NULL AND message IS NOT NULL) as errors')
            ->orderBy('started_at', 'DESC')
            ->join('e', self::TABLE_CHANNEL, 'ch', 'ch.id = e.channel_id')
            ->setMaxResults(10)
            ->execute()
            ->fetchAll();
    }

    /**
     * @param ExportId $exportId
     *
     * @return array
     */
    public function getInformation(ExportId $exportId): array
    {
        $query = $this->getQuery();

        return $query
            ->addSelect('(SELECT count(*) FROM exporter.export_line el WHERE el.export_id = e.id 
                                AND processed_at IS NOT NULL) as processed')
            ->addSelect('(SELECT count(*) FROM exporter.export_line el WHERE el.export_id = e.id 
                                AND processed_at IS NOT NULL AND message IS NOT NULL) as errors')
            ->where($query->expr()->eq('id', ':exportId'))
            ->setParameter(':exportId', $exportId->getValue())
            ->execute()
            ->fetch();
    }

    /**
     * @param ChannelId $channelId
     *
     * @return \DateTime|null
     *
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
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('e.id, e.status, e.started_at, e.ended_at')
            ->from(self::TABLE, 'e');
    }
}
