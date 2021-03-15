<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Comment\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Comment\Domain\Query\CommentGridQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;

class DbalCommentGridQuery implements CommentGridQueryInterface
{
    private const COMMENT_TABLE = 'comment';
    private const USER_TABLE = 'users';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getDataSet(Language $language): QueryBuilder
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
