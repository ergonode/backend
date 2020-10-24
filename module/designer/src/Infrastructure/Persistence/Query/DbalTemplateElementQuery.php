<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Designer\Domain\Query\TemplateElementQueryInterface;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;

class DbalTemplateElementQuery implements TemplateElementQueryInterface
{
    private const TABLE = 'designer.element_type';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getDataSet(): DataSetInterface
    {
        return new DbalDataSet($this->getQuery());
    }

    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('*')
            ->from(self::TABLE, 'e');
    }
}
