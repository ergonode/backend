<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;
use Ergonode\Importer\Domain\Query\ImportQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportErrorId;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use Symfony\Contracts\Translation\TranslatorInterface;

class DbalImportQuery implements ImportQueryInterface
{
    private Connection $connection;

    private TranslatorInterface $translator;

    public function __construct(Connection $connection, TranslatorInterface $translator)
    {
        $this->connection = $connection;
        $this->translator = $translator;
    }

    /**
     * @return array
     */
    public function getLineContent(ImportErrorId $id): array
    {
        $qb = $this->connection->createQueryBuilder();
        $record = $qb
            ->select('line')
            ->from('importer.import_error')
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
        $qb = $this->getQuery();
        $qb->andWhere($qb->expr()->eq('source_id', ':sourceId'))
            ->setParameter('sourceId', $id->getValue());

        return new DbalDataSet($qb);
    }

    public function getErrorDataSet(ImportId $id, Language $language): DataSetInterface
    {
        $query = $this->connection->createQueryBuilder();

        $query->select('il.import_id AS id, il.created_at, il.message')
            ->from('importer.import_error', 'il')
            ->where($query->expr()->eq('il.import_id', ':importId'))
            ->andWhere($query->expr()->isNotNull('il.message'));

        $result = $this->connection->createQueryBuilder();
        $result->select('*');
        $result->from(sprintf('(%s)', $query->getSQL()), 't')
            ->setParameter(':importId', $id->getValue());

        return new DbalDataSet($result);
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

    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('id, status, records, source_id, created_at, updated_at, started_at, ended_at')
            ->addSelect(
                '(SELECT count(*)
                        FROM importer.import_error il
                        WHERE il.import_id = i.id
                        AND il.message IS NOT NULL) AS errors'
            )
            ->from('importer.import', 'i');
    }
}
