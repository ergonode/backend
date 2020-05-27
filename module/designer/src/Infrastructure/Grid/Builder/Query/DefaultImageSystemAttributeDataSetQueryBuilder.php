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
use Ergonode\Designer\Domain\Entity\Attribute\DefaultImageSystemAttribute;
use Ergonode\Product\Infrastructure\Grid\Builder\Query\AttributeDataSetQueryBuilderInterface;

/**
 */
class DefaultImageSystemAttributeDataSetQueryBuilder implements AttributeDataSetQueryBuilderInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof DefaultImageSystemAttribute;
    }

    /**
     * {@inheritDoc}
     */
    public function addSelect(QueryBuilder $query, string $key, AbstractAttribute $attribute, Language $language): void
    {
        $sql = sprintf('(
       SELECT pvtdi.value
FROM designer.product dpt
         INNER JOIN public.product ppt ON ppt.id = p.id
         INNER JOIN designer.template dtt ON dpt.template_id = dtt.id
         LEFT JOIN public.product_value ppvtdi
                   ON ppvtdi.product_id = p.id AND
                      ppvtdi.attribute_id = dtt.default_image
         LEFT JOIN public.value_translation pvtdi ON ppvtdi.value_id = pvtdi.value_id
WHERE dpt.product_id = p.id) as "%s"', $key);

        $query->addSelect($sql);
    }
}
