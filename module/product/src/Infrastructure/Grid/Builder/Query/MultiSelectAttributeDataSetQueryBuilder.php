<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Grid\Builder\Query;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
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
        $info = $this->query->getLanguageNodeInfo($language);

        $query->addSelect(sprintf(
            '
            (
                SELECT to_jsonb(regexp_split_to_array(value,\',\')) FROM product_value pv
                JOIN value_translation vt ON vt.value_id = pv.value_id
                LEFT JOIN language_tree lt ON lt.code = vt.language
                WHERE pv.attribute_id = \'%s\'
                AND pv.product_id = p.id
                AND lt.lft <= %s AND lt.rgt >= %s
                ORDER BY lft DESC NULLS LAST
                LIMIT 1      
            ) AS "%s"',
            $attribute->getId()->getValue(),
            $info['lft'],
            $info['rgt'],
            $key
        ));
    }
}
