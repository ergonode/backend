<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Infrastructure\Grid\Builder\Query;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Entity\Attribute\DefaultLabelSystemAttribute;
use Ergonode\Product\Infrastructure\Grid\Builder\Query\AttributeDataSetQueryBuilderInterface;

/**
 */
class DefaultLabelSystemAttributeDataSetQueryBuilder implements AttributeDataSetQueryBuilderInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof DefaultLabelSystemAttribute;
    }

    /**
     * {@inheritDoc}
     */
    public function addSelect(QueryBuilder $query, string $key, AbstractAttribute $attribute, Language $language): void
    {
        $sql = sprintf('(SELECT
       CASE
           WHEN dtt.default_text IS NULL THEN ppt.sku::VARCHAR
           WHEN dtt.default_text IS NOT NULL THEN pvtdt.value::VARCHAR
           END
FROM designer.product dpt
         INNER JOIN public.product ppt ON ppt.id = p.id
         INNER JOIN designer.template dtt ON dpt.template_id = dtt.id
         LEFT JOIN public.product_value ppvtdt
                   ON ppvtdt.product_id = p.id AND
                      ppvtdt.attribute_id = dtt.default_text
         LEFT JOIN public.value_translation pvtdt
                   ON (pvtdt.language = \'%s\' OR pvtdt.language IS NULL) AND ppvtdt.value_id = pvtdt.value_id
WHERE dpt.product_id = p.id) as "%s"', $language->getCode(), $key);

        $query->addSelect($sql);
    }
}
