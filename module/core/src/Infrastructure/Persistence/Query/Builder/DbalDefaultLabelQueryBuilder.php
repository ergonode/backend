<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Persistence\Query\Builder;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\Query\Builder\DefaultLabelQueryBuilderInterface;

class DbalDefaultLabelQueryBuilder implements DefaultLabelQueryBuilderInterface
{
    public function addSelect(QueryBuilder $query, int $lft, int $rgt): void
    {
        $sql = sprintf('(SELECT
            CASE
                WHEN dtt.default_label IS NULL THEN p.sku::VARCHAR
                WHEN dtt.default_label IS NOT NULL THEN pvtdl.value::VARCHAR
                END AS default_label
            FROM public.product ppdi
            INNER JOIN designer.template dtt ON ppdi.template_id = dtt.id
            LEFT JOIN public.product_value ppvtdl
                ON ppvtdl.product_id = ppdi.id AND ppvtdl.attribute_id = dtt.default_label
            LEFT JOIN public.value_translation pvtdl ON ppvtdl.value_id = pvtdl.value_id
            LEFT JOIN public.language_tree pltdl ON pltdl.code = pvtdl.language
            WHERE ((pltdl.lft <= %s AND pltdl.rgt >= %s) OR pltdl.lft IS NULL) AND ppdi.id = p.id 
            ORDER BY pltdl.lft DESC NULLS LAST
            LIMIT 1)', $lft, $rgt);
        $query->addSelect($sql);
    }
}
