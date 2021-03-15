<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Infrastructure\Grid\Builder\Query;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Entity\Attribute\DefaultLabelSystemAttribute;
use Ergonode\Product\Infrastructure\Grid\Builder\Query\AttributeDataSetQueryBuilderInterface;
use Ergonode\Product\Infrastructure\Strategy\ProductAttributeLanguageResolver;

class DefaultLabelSystemAttributeDataSetQueryBuilder implements AttributeDataSetQueryBuilderInterface
{
    protected LanguageQueryInterface $query;

    protected ProductAttributeLanguageResolver $resolver;

    public function __construct(LanguageQueryInterface $query, ProductAttributeLanguageResolver $resolver)
    {
        $this->query = $query;
        $this->resolver = $resolver;
    }

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

        $info = $this->query->getLanguageNodeInfo($this->resolver->resolve($attribute, $language));

        $sql = sprintf(
            '(SELECT 
           CASE
           WHEN dt.default_label IS NULL THEN pp.sku::VARCHAR
           WHEN dt.default_label IS NOT NULL THEN pvtdl.value::VARCHAR
           END
FROM public.product pp
         INNER JOIN designer.template dt ON pp.template_id = dt.id
         LEFT JOIN public.product_value ppvdl
                   ON ppvdl.product_id = p.id AND
                      ppvdl.attribute_id = dt.default_label
         LEFT JOIN public.value_translation pvtdl
                   ON ppvdl.value_id = pvtdl.value_id
         LEFT JOIN public.language_tree ppvlt ON ppvlt.code = pvtdl.language
WHERE ((ppvlt.lft <= %s AND ppvlt.rgt >= %s) OR ppvlt.lft IS NULL) AND pp.id = p.id
ORDER BY lft DESC NULLS LAST
LIMIT 1 ) as "%s"',
            $info['lft'],
            $info['rgt'],
            $key
        );

        $query->addSelect($sql);
    }
}
