<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Comment\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Comment\Domain\Query\CommentQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\Factory\DbalDataSetFactory;

class DbalCommentQuery implements CommentQueryInterface
{
    private const COMMENT_TABLE = 'comment';
    private const USER_TABLE = 'users';

    private Connection $connection;

    private DbalDataSetFactory $dataSetFactory;

    public function __construct(Connection $connection, DbalDataSetFactory $dataSetFactory)
    {
        $this->connection = $connection;
        $this->dataSetFactory = $dataSetFactory;
    }

    public function getDataSet(Language $language): DataSetInterface
    {
        $query = $this->getQuery();

        $result = $this->connection->createQueryBuilder();
        $result->select('*');
        $result->from(sprintf('(%s)', $query->getSQL()), 't');

        return $this->dataSetFactory->create($result);
    }

    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->addSelect('n.*')
            ->addSelect('coalesce(u.first_name || \' \' || u.last_name, null) AS author')
            ->addSelect('u.id AS user_id')
            ->addSelect('CASE WHEN u.avatar THEN u.id ELSE null END as avatar_filename')
            ->from(self::COMMENT_TABLE, 'n')
            ->leftJoin('n', self::USER_TABLE, 'u', 'n.author_id = u.id');
    }
}
