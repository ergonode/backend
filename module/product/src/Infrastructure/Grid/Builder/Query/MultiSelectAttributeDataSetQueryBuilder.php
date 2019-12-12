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
class MultiSelectAttributeDataSetQueryBuilder implements AttributeDataSetQueryBuilderInterface
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
        $query->addSelect(sprintf(
            '(SELECT jsonb_agg(value) FROM value_translation vt JOIN product_value pv ON  pv.value_id = vt.value_id WHERE pv.attribute_id = \'%s\' AND (vt.language = \'%s\' OR vt.language IS NULL) AND pv.product_id = p.id LIMIT 1) AS "%s"',
            $attribute->getId()->getValue(),
            $language->getCode(),
            $key
        ));
    }
}
