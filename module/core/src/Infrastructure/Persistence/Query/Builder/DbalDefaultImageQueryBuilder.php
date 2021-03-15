<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Persistence\Query\Builder;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\Query\Builder\DefaultImageQueryBuilderInterface;

class DbalDefaultImageQueryBuilder implements DefaultImageQueryBuilderInterface
{
    public function addSelect(QueryBuilder $query, int $lft, int $rgt): void
    {
        $sql = sprintf('
        (
            SELECT pvtdi.value AS default_image
            FROM public.product ppdi
            INNER JOIN designer.template dtt ON ppdi.template_id = dtt.id
            LEFT JOIN public.product_value ppvtdi
                ON ppvtdi.product_id = ppdi.id AND ppvtdi.attribute_id = dtt.default_image
            LEFT JOIN public.value_translation pvtdi ON ppvtdi.value_id = pvtdi.value_id
            LEFT JOIN public.language_tree pltdi ON pltdi.code = pvtdi.language
            WHERE ((pltdi.lft <= %s AND pltdi.rgt >= %s) OR pltdi.lft IS NULL) AND ppdi.id = p.id
            ORDER BY pltdi.lft DESC NULLS LAST
            LIMIT 1
        )', $lft, $rgt);

        $query->addSelect($sql);
    }
}
