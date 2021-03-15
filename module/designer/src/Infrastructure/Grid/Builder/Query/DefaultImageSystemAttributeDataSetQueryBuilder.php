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
use Ergonode\Designer\Domain\Entity\Attribute\DefaultImageSystemAttribute;
use Ergonode\Product\Infrastructure\Grid\Builder\Query\AttributeDataSetQueryBuilderInterface;
use Ergonode\Product\Infrastructure\Strategy\ProductAttributeLanguageResolver;

class DefaultImageSystemAttributeDataSetQueryBuilder implements AttributeDataSetQueryBuilderInterface
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
        return $attribute instanceof DefaultImageSystemAttribute;
    }

    /**
     * {@inheritDoc}
     */
    public function addSelect(QueryBuilder $query, string $key, AbstractAttribute $attribute, Language $language): void
    {
        $info = $this->query->getLanguageNodeInfo($this->resolver->resolve($attribute, $language));

        $sql = sprintf(
            '(
                SELECT pvtdi.value
                FROM public.product pp
                         INNER JOIN designer.template dt ON pp.template_id = dt.id
                         LEFT JOIN public.product_value ppvdi
                                   ON ppvdi.product_id = p.id AND
                                      ppvdi.attribute_id = dt.default_image
                         LEFT JOIN public.value_translation pvtdi ON ppvdi.value_id = pvtdi.value_id
                         LEFT JOIN public.language_tree ppvlt ON ppvlt.code = pvtdi.language
                WHERE ((ppvlt.lft <= %s AND ppvlt.rgt >= %s) OR ppvlt.lft IS NULL) AND pp.id = p.id     
                ORDER BY lft DESC NULLS LAST
                LIMIT 1) as "%s"',
            $info['lft'],
            $info['rgt'],
            $key
        );

        $query->addSelect($sql);
    }
}
