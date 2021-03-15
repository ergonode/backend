<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Grid\Builder\Query;

use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Product\Infrastructure\Strategy\ProductAttributeLanguageResolver;

abstract class AbstractAttributeDataSetBuilder implements AttributeDataSetQueryBuilderInterface
{
    protected LanguageQueryInterface $query;

    protected ProductAttributeLanguageResolver $resolver;

    public function __construct(LanguageQueryInterface $query, ProductAttributeLanguageResolver $resolver)
    {
        $this->query = $query;
        $this->resolver = $resolver;
    }

    public function addSelect(QueryBuilder $query, string $key, AbstractAttribute $attribute, Language $language): void
    {
        $info = $this->query->getLanguageNodeInfo($this->resolver->resolve($attribute, $language));

        $sql = sprintf(
            '(SELECT 	
			            DISTINCT ON (product_id) product_id, 
			            value AS "%s" 
		            FROM value_translation vt 
		            JOIN product_value pv ON pv.value_id = vt.value_id
		            LEFT JOIN language_tree lt ON lt.code = vt.language
		            WHERE attribute_id = \'%s\'
                    AND lt.lft <= %s AND lt.rgt >= %s
		            ORDER BY product_id, lft DESC NULLS LAST
		        )',
            $key,
            $attribute->getId()->getValue(),
            $info['lft'],
            $info['rgt'],
        );

        $query->addSelect(sprintf('"%s"', $key));
        $query->leftJoin('p', $sql, sprintf('"%s_JT"', $key), sprintf('"%s_JT".product_id = p.id', $key));
    }
}
