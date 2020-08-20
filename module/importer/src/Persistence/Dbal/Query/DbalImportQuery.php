<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\SharedKernel\Domain\Aggregate\ImportLineId;
use Ergonode\Importer\Domain\Query\ImportQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;

/**
 */
class DbalImportQuery implements ImportQueryInterface
{
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
     * @param ImportLineId $id
     *
     * @return array
     */
    public function getLineContent(ImportLineId $id): array
    {
        $qb = $this->connection->createQueryBuilder();
        $record = $qb
            ->select('line')
            ->from('importer.import_line')
            ->where($qb->expr()->eq('id', ':id'))
            ->setParameter(':id', $id->getValue())
            ->execute()
            ->fetch();

        if ($record) {
            return json_decode($record['line'], true, 512, JSON_THROW_ON_ERROR);
        }

        return [];
    }

    /**
     * @param SourceId $id
     *
     * @return DataSetInterface
     */
    public function getDataSet(SourceId $id): DataSetInterface
    {
        $qb = $this->getQuery();
        $qb->andWhere($qb->expr()->eq('source_id', ':sourceId'))
            ->setParameter('sourceId', $id->getValue());

        return new DbalDataSet($qb);
    }

    /**
     * @param ImportId $id
     * @param Language $language
     *
     * @return DataSetInterface
     */
    public function getErrorDataSet(ImportId $id, Language $language): DataSetInterface
    {
        $query = $this->connection->createQueryBuilder();

        $query->select('il.import_id AS id, il.line, il.processed_at, il.message')
            ->from('importer.import_line', 'il')
            ->where($query->expr()->eq('il.import_id', ':importId'))
            ->andWhere($query->expr()->isNotNull('il.message'));

        $result = $this->connection->createQueryBuilder();
        $result->select('*');
        $result->from(sprintf('(%s)', $query->getSQL()), 't')
            ->setParameter(':importId', $id->getValue());

        return new DbalDataSet($result);
    }

    /**
     * @param ImportId $id
     * @param Language $language
     *
     * @return array
     */
    public function getInformation(ImportId $id, Language $language): array
    {
        $query = $this->getQuery();

        return $query
            ->where($query->expr()->eq('id', ':importId'))
            ->setParameter(':importId', $id->getValue())
            ->execute()
            ->fetch();
    }

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('id, status, source_id, created_at, updated_at, started_at, ended_at')
            ->addSelect('(SELECT count(*) FROM importer.import_line il WHERE il.import_id = i.id) AS records')
            ->addSelect(
                '(SELECT count(*)
                        FROM importer.import_line il
                        WHERE il.import_id = i.id
                        AND il.message IS NOT NULL) AS errors'
            )
            ->from('importer.import', 'i');
    }
}
