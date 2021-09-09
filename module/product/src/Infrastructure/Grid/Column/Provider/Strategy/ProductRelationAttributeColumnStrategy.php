<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\ColumnInterface;
use Ergonode\Product\Domain\Entity\Attribute\ProductRelationAttribute;
use Ergonode\Product\Infrastructure\Grid\Column\ProductRelationColumn;

class ProductRelationAttributeColumnStrategy implements AttributeColumnStrategyInterface
{
    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof ProductRelationAttribute;
    }

    public function create(AbstractAttribute $attribute, Language $language): ColumnInterface
    {
        return new ProductRelationColumn(
            $attribute->getCode()->getValue(),
            $attribute->getLabel()->get($language)
        );
    }
}
