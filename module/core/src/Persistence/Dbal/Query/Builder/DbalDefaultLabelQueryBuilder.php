<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Persistence\Dbal\Query\Builder;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\Query\Builder\DefaultLabelQueryBuilderInterface;

/**
 */
class DbalDefaultLabelQueryBuilder implements DefaultLabelQueryBuilderInterface
{
    /**
     * @param QueryBuilder $query
     * @param array        $info
     */
    public function addSelect(QueryBuilder $query, array $info): void
    {
        $sql = sprintf('(SELECT
             CASE
                 WHEN dtt.default_text IS NULL THEN ppt.sku::VARCHAR
                 WHEN dtt.default_text IS NOT NULL THEN pvtdt.value::VARCHAR
                 END       as default_label
                 FROM public.product ppdi
               INNER JOIN designer.template dtt ON ppdi.template_id = dtt.id
               LEFT JOIN public.product_value ppvtdt
                         ON ppvtdt.product_id = ppdi.id AND ppvtdt.attribute_id = dtt.default_text
               LEFT JOIN public.value_translation pvtdt ON ppvtdt.value_id = pvtdt.value_id
               LEFT JOIN public.language_tree pltdt ON pltdt.code = pvtdt.language
        WHERE ((pltdt.lft <= %s AND pltdt.rgt >= %s) OR pltdt.lft IS NULL) AND ppdi.id = ppt.id 
      ORDER BY pltdt.lft DESC NULLS LAST
                LIMIT 1)', $info['lft'], $info['rgt']);
        $query->addSelect($sql);
    }
}
