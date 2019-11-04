<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Note\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;
use Ergonode\Note\Domain\Query\NoteQueryInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class DbalNoteQuery implements NoteQueryInterface
{
    private const NOTE_TABLE = 'note';
    private const USER_TABLE = 'users';

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param Language $language
     * @param Uuid     $uuid
     *
     * @return DataSetInterface
     */
    public function getDataSet(Language $language, Uuid $uuid): DataSetInterface
    {
        $query = $this->getQuery();
        $query->where($query->expr()->eq('object_id', ':uuid'));

        $result = $this->connection->createQueryBuilder();
        $result->select('*');
        $result->from(sprintf('(%s)', $query->getSQL()), 't');
        $result->setParameter(':uuid', $uuid->toString());

        return new DbalDataSet($result);
    }

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->addSelect('n.*')
            ->addSelect('coalesce(u.first_name || \' \' || u.last_name, null) AS author')
            ->addSelect('avatar_id')
            ->from(self::NOTE_TABLE, 'n')
            ->leftJoin('n', self::USER_TABLE, 'u', 'n.author_id = u.id');
    }
}
