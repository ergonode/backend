<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Attribute\Domain\Query\AttributeGridQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\Factory\DbalDataSetFactory;

class DbalAttributeGridQuery implements AttributeGridQueryInterface
{
    private const ATTRIBUTE_TABLE = 'attribute';
    private const FIELDS = [
        'a.id',
        'a.index',
        'a.code',
        'a.type',
        'a.system',
        'a.scope',
    ];

    private Connection $connection;

    private DbalDataSetFactory $dataSetFactory;

    public function __construct(Connection $connection, DbalDataSetFactory $dataSetFactory)
    {
        $this->connection = $connection;
        $this->dataSetFactory = $dataSetFactory;
    }

    public function getDataSet(Language $language, bool $system = false): DataSetInterface
    {
        $query = $this->getQuery();
        $query->addSelect(
            sprintf(
                '(SELECT value FROM value_translation t WHERE t.value_id = a.label AND t.language = \'%s\') '.
                ' AS label',
                $language->getCode()
            )
        );
        if ($system) {
            $query->where('a.system = true');
        } else {
            $query->where('a.system = false');
        }

        $result = $this->connection->createQueryBuilder();
        $result->select('*');
        $result->from(sprintf('(%s)', $query->getSQL()), 't');

        return $this->dataSetFactory->create($result);
    }

    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(self::FIELDS)
            ->addSelect(
                '(SELECT COALESCE(jsonb_agg(ag.id),\'[]\') FROM attribute_group_attribute aga  '.
                ' JOIN attribute_group ag ON aga.attribute_group_id = ag.id WHERE aga.attribute_id = a.id) AS groups'
            )
            ->from(self::ATTRIBUTE_TABLE, 'a');
    }
}
