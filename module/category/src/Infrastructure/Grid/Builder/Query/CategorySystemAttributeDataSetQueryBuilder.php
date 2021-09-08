<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Infrastructure\Grid\Builder\Query;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Category\Domain\Entity\Attribute\CategorySystemAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Product\Infrastructure\Grid\Builder\Query\AttributeDataSetQueryBuilderInterface;

class CategorySystemAttributeDataSetQueryBuilder implements AttributeDataSetQueryBuilderInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof CategorySystemAttribute;
    }

    /**
     * {@inheritDoc}
     */
    public function addSelect(
        QueryBuilder $query,
        string $key,
        AbstractAttribute $attribute,
        Language $language
    ): void {

        $sql = sprintf('(SELECT DISTINCT ON (product_id) jsonb_agg(category_id) as "%s", product_id
                          FROM product_category pcp
                          group by product_id)', $key);

        $query->addSelect(sprintf('"%s"', $key));
        $query->leftJoin('p', $sql, sprintf('"%s_JT"', $key), sprintf('"%s_JT".product_id = p.id', $key));
    }
}
