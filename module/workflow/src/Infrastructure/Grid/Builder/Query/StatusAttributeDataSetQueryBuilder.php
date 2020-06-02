<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Grid\Builder\Query;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Product\Infrastructure\Grid\Builder\Query\AbstractAttributeDataSetBuilder;
use Ergonode\Workflow\Domain\Entity\Attribute\StatusSystemAttribute;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
class StatusAttributeDataSetQueryBuilder extends AbstractAttributeDataSetBuilder
{
    /**
     * {@inheritDoc}
     */
    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof StatusSystemAttribute;
    }

    /**
     * @param QueryBuilder      $query
     * @param string            $key
     * @param AbstractAttribute $attribute
     * @param Language          $language
     */
    public function addSelect(QueryBuilder $query, string $key, AbstractAttribute $attribute, Language $language): void
    {
        $query->addSelect(sprintf(
            '(
                SELECT value FROM product_value pv
                JOIN value_translation vt ON vt.value_id = pv.value_id
                WHERE pv.attribute_id = \'%s\'
                AND pv.product_id = p.id
                AND vt.language IS NULL
                LIMIT 1           
            ) AS "%s"',
            $attribute->getId()->getValue(),
            $key
        ));
    }
}
