<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Infrastructure\Grid\Builder\Query;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Completeness\Domain\Entity\Attribute\CompletenessSystemAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Product\Infrastructure\Grid\Builder\Query\AttributeDataSetQueryBuilderInterface;

class CompletenessSystemAttributeDataSetQueryBuilder implements AttributeDataSetQueryBuilderInterface
{

    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof CompletenessSystemAttribute;
    }

    public function addSelect(QueryBuilder $query, string $key, AbstractAttribute $attribute, Language $language): void
    {

        $sql = sprintf(
            '(
                SELECT completeness->>\'%s\'
                FROM public.product pp
                         LEFT JOIN public.product_completeness ppc ON ppc.product_id = pp.id
                WHERE pp.id = p.id     
                LIMIT 1) as "%s"',
            $language->getCode(),
            $key
        );

        $query->addSelect($sql);
    }
}
