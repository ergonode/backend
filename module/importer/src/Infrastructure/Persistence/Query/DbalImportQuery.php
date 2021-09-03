<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\Factory\DbalDataSetFactory;
use Ergonode\Importer\Domain\Query\ImportQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportErrorId;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use Symfony\Contracts\Translation\TranslatorInterface;

class DbalImportQuery implements ImportQueryInterface
{
    private const TABLE = 'importer.import';
    private const TABLE_ERROR = 'importer.import_error';
    private const TABLE_SOURCE = 'importer.source';

    private Connection $connection;

    private TranslatorInterface $translator;

    private DbalDataSetFactory $dataSetFactory;

    public function __construct(
        Connection $connection,
        TranslatorInterface $translator,
        DbalDataSetFactory $dataSetFactory
    ) {
        $this->connection = $connection;
        $this->translator = $translator;
        $this->dataSetFactory = $dataSetFactory;
    }

    /**
     * @return array
     */
    public function getLineContent(ImportErrorId $id): array
    {
        $qb = $this->connection->createQueryBuilder();
        $record = $qb
            ->select('line')
            ->from(self::TABLE_ERROR)
            ->where($qb->expr()->eq('id', ':id'))
            ->setParameter(':id', $id->getValue())
            ->execute()
            ->fetch();

        if ($record) {
            return json_decode($record['line'], true, 512, JSON_THROW_ON_ERROR);
        }

        return [];
    }

    public function getDataSet(SourceId $id): DataSetInterface
    {
        $query = $this->getQuery();
        $query->andWhere($query->expr()->eq('source_id', ':sourceId'))
            ->setParameter('sourceId', $id->getValue());

        return $this->dataSetFactory->create($query);
    }

    public function getErrorDataSet(ImportId $id, Language $language): DataSetInterface
    {
        $query = $this->connection->createQueryBuilder();

        $query->select('il.import_id AS id, il.created_at, il.message, il.parameters')
            ->from(self::TABLE_ERROR, 'il')
            ->where($query->expr()->eq('il.import_id', ':importId'))
            ->andWhere($query->expr()->isNotNull('il.message'));

        $result = $this->connection->createQueryBuilder();
        $result->select('*');
        $result->from(sprintf('(%s)', $query->getSQL()), 't')
            ->setParameter(':importId', $id->getValue());

        return $this->dataSetFactory->create($result);
    }

    /**
     * @return array
     */
    public function getProfileInfo(Language $language): array
    {
        return $this->connection->createQueryBuilder()
            ->select('i.id, status, started_at, ended_at')
            ->addSelect('s.name')
            ->addSelect('(CASE WHEN ii.items IS NULL THEN 0 ELSE ii.items END) AS items')
            ->addSelect('(CASE WHEN ip.processed IS NULL THEN 0 ELSE ip.processed END) AS processed')
            ->addSelect('(CASE WHEN ic.succeeded IS NULL THEN 0 ELSE ic.succeeded END) AS succeeded')
            ->addSelect('(CASE WHEN ie.errors IS NULL THEN 0 ELSE ie.errors END) AS errors')
            ->from(self::TABLE, 'i')
            ->orderBy('started_at', 'DESC')
            ->join('i', self::TABLE_SOURCE, 's', 's.id = i.source_id')
            ->leftJoin(
                'i',
                '(SELECT count(*) AS items, import_id FROM importer.import_line GROUP BY import_id)',
                'ii',
                'ii.import_id = i.id'
            )
            ->leftJoin(
                'i',
                '(SELECT count(*) AS processed, import_id FROM importer.import_line 
                WHERE status IS NOT NULL GROUP BY import_id)',
                'ip',
                'ip.import_id = i.id'
            )
            ->leftJoin(
                'i',
                '(SELECT count(*) AS succeeded, import_id FROM importer.import_line 
                WHERE status = \'success\' GROUP BY import_id)',
                'ic',
                'ic.import_id = i.id'
            )
            ->leftJoin(
                'i',
                '(SELECT count(*) AS errors, import_id FROM importer.import_error GROUP BY import_id)',
                'ie',
                'ie.import_id = i.id'
            )
            ->setMaxResults(10)
            ->execute()
            ->fetchAll();
    }

    /**
     * @return array
     */
    public function getInformation(ImportId $id, Language $language): array
    {
        $query = $this->getQuery();

        $result = $query
            ->where($query->expr()->eq('id', ':importId'))
            ->setParameter(':importId', $id->getValue())
            ->execute()
            ->fetch();

        $result['status'] = $this->translator->trans($result['status'], [], 'import', $language->getCode());

        return $result;
    }

    /**
     * @return ImportId[]
     */
    public function getImportIdsBySourceId(SourceId $sourceId): array
    {
        $qb = $this->connection->createQueryBuilder();

        $result = $qb->select('i.id')
            ->from(self::TABLE, 'i')
            ->where($qb->expr()->eq('i.source_id', ':sourceId'))
            ->setParameter(':sourceId', $sourceId->getValue())
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);

        if (false === $result) {
            $result = [];
        }

        foreach ($result as &$item) {
            $item = new ImportId($item);
        }

        return $result;
    }

    public function getSourceTypeByImportId(ImportId $importId): ?string
    {
        $qb = $this->connection->createQueryBuilder();

        $result = $qb->select('s.type')
            ->join('i', self::TABLE_SOURCE, 's', 's.id = i.source_id')
            ->where($qb->expr()->eq('i.id', ':importId'))
            ->setParameter(':importId', $importId->getValue())
            ->from(self::TABLE, 'i')
            ->execute()
            ->fetch();

        if ($result) {
            return $result['type'];
        }

        return null;
    }

    public function getFileNameByImportId(ImportId $importId): ?string
    {
        $qb = $this->connection->createQueryBuilder();

        $result = $qb->select('i.file')
            ->where($qb->expr()->eq('i.id', ':importId'))
            ->setParameter(':importId', $importId->getValue())
            ->from(self::TABLE, 'i')
            ->execute()
            ->fetch();

        if ($result) {
            return $result['file'];
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function findActiveImport(SourceId $sourceId): array
    {
        $qb = $this->connection->createQueryBuilder();

        $result = $qb->select('i.id')
            ->from(self::TABLE, 'i')
            ->where($qb->expr()->eq('i.source_id', ':sourceId'))
            ->setParameter(':sourceId', $sourceId->getValue())
            ->andWhere($qb->expr()->isNull('i.ended_at'))
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);

        foreach ($result as &$item) {
            $item = new ImportId($item);
        }

        return $result;
    }

    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('id, status, source_id, created_at, updated_at, started_at, ended_at')
            ->addSelect(
                '(SELECT count(*)
                        FROM importer.import_error il
                        WHERE il.import_id = i.id
                        AND il.message IS NOT NULL) AS errors'
            )
            ->addSelect(
                '(SELECT count(*)
                        FROM importer.import_line il
                        WHERE il.import_id = i.id
                        ) AS records'
            )
            ->from(self::TABLE, 'i');
    }
}
