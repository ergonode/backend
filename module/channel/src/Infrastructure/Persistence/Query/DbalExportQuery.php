<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Channel\Domain\Query\ExportQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Channel\Domain\ValueObject\ExportStatus;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\Factory\DbalDataSetFactory;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;

class DbalExportQuery implements ExportQueryInterface
{
    private const TABLE = 'exporter.export';
    private const TABLE_ERROR = 'exporter.export_error';
    private const TABLE_CHANNEL = 'exporter.channel';

    private Connection $connection;

    private DbalDataSetFactory $dataSetFactory;

    public function __construct(Connection $connection, DbalDataSetFactory $dataSetFactory)
    {
        $this->connection = $connection;
        $this->dataSetFactory = $dataSetFactory;
    }

    public function getDataSet(ChannelId $channelId, Language $language): DataSetInterface
    {
        $query = $this->getQuery();
        $query->addSelect('e.channel_id');
        $query->andWhere($query->expr()->eq('channel_id', ':channelId'));

        $result = $this->connection->createQueryBuilder();
        $result->select('*');
        $result->from(sprintf('(%s)', $query->getSQL()), 't')
            ->setParameter(':channelId', $channelId->getValue());

        return $this->dataSetFactory->create($result);
    }

    public function getErrorDataSet(ExportId $exportId, Language $language): DataSetInterface
    {
        $query = $this->connection->createQueryBuilder();
        $query
            ->select('id, created_at, message, parameters')
            ->from(self::TABLE_ERROR)
            ->where($query->expr()->eq('export_id', ':exportId'))
            ->setParameter(':exportId', $exportId->getValue());

        return $this->dataSetFactory->create($query);
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
            ->join('e', self::TABLE_CHANNEL, 'ch', 'ch.id = e.channel_id')
            ->where($qb->expr()->eq('e.channel_id', ':channelId'))
            ->setParameter(':channelId', $channelId->getValue())
            ->from(self::TABLE, 'e')
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

    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('e.id, e.status, e.started_at, e.ended_at')
            ->from(self::TABLE, 'e');
    }
}
