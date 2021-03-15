<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Designer\Domain\Query\TemplateGridQueryInterface;

class DbalTemplateGridQuery implements TemplateGridQueryInterface
{
    private const TEMPLATE_TABLE = 'designer.template';
    private const ATTRIBUTE_TABLE = 'public.attribute';
    private const FIELDS = [
        't.id',
        't.name',
        't.default_image',
        't.default_label',
        't.image_id',
        't.template_group_id AS group_id',
    ];

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getGridQuery(): QueryBuilder
    {
        $query = $this->connection->createQueryBuilder();

        return $query
            ->select(self::FIELDS)
            ->addSelect('COALESCE(tet.code, \'SKU\') as default_label_attribute')
            ->addSelect('tei.code as default_image_attribute')
            ->from(self::TEMPLATE_TABLE, 't')
            ->leftJoin('t', self::ATTRIBUTE_TABLE, 'tet', 't.default_label = tet.id')
            ->leftJoin('t', self::ATTRIBUTE_TABLE, 'tei', 't.default_image = tei.id');
    }
}
