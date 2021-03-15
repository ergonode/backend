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
    /**
     * {@inheritDoc}
     */
    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof ProductCollectionSystemAttribute;
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
        $query->addSelect(
            sprintf(
                '(
                    SELECT jsonb_agg(product_collection_id) 
                    FROM product_collection_element ce 
                    WHERE ce.product_id = p.id LIMIT 1
                ) AS "%s"',
                $key
            )
        );
    }
}
