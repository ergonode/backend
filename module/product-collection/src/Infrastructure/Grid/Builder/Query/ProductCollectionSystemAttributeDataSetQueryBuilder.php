<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Infrastructure\Grid\Builder\Query;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Product\Infrastructure\Grid\Builder\Query\AttributeDataSetQueryBuilderInterface;
use Ergonode\ProductCollection\Domain\Entity\Attribute\ProductCollectionSystemAttribute;

class ProductCollectionSystemAttributeDataSetQueryBuilder implements AttributeDataSetQueryBuilderInterface
{
    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof ProductCollectionSystemAttribute;
    }

    public function addSelect(
        QueryBuilder $query,
        string $key,
        AbstractAttribute $attribute,
        Language $language
    ): void {
        $sql = sprintf(
            '(SELECT
                        product_id, 
                        jsonb_agg(product_collection_id) AS "%s"
                        FROM product_collection_element ce 
						GROUP BY product_id
                    ) 
                ',
            $key
        );

        $query->addSelect(sprintf('"%s"', $key));
        $query->leftJoin('p', $sql, sprintf('"%s_JT"', $key), sprintf('"%s_JT".product_id = p.id', $key));
    }
}
