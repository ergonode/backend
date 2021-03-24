<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Grid\Builder\Query;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Product\Domain\Entity\Attribute\CreatedBySystemAttribute;

class CreatedBySystemAttributeDataSetQueryBuilder extends AbstractAttributeDataSetBuilder
{
    /**
     * {@inheritDoc}
     */
    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof CreatedBySystemAttribute;
    }

    /**
     * {@inheritDoc}
     */
    public function addSelect(QueryBuilder $query, string $key, AbstractAttribute $attribute, Language $language): void
    {
        $query->addSelect(sprintf(
            '(
                SELECT value 
                FROM product_value pv
                JOIN value_translation vt ON vt.value_id = pv.value_id
                WHERE pv.attribute_id = \'%s\'
                AND pv.product_id = p.id
                AND vt."language" IS NULL
                LIMIT 1           
            ) AS "%s"',
            $attribute->getId()->getValue(),
            $key
        ));
    }
}
