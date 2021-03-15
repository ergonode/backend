<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Grid\Builder\Query;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\Core\Domain\ValueObject\Language;

class MultiSelectAttributeDataSetQueryBuilder extends AbstractAttributeDataSetBuilder
{
    /**
     * {@inheritDoc}
     */
    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof MultiSelectAttribute;
    }

    /**
     * {@inheritDoc}
     */
    public function addSelect(QueryBuilder $query, string $key, AbstractAttribute $attribute, Language $language): void
    {
        $info = $this->query->getLanguageNodeInfo($this->resolver->resolve($attribute, $language));

        $sql = sprintf(
            '(SELECT 	
			            DISTINCT ON (product_id) product_id, 
			            to_jsonb(regexp_split_to_array(value,\',\')) AS "%s" 
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
