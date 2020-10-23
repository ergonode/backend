<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Query;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Product\Domain\Entity\AbstractAssociatedProduct;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

interface ProductChildrenQueryInterface
{
    public function getDataSet(ProductId $productId, Language $language): DataSetInterface;

    /**
     * @param AbstractAttribute[] $bindingAttributes
     */
    public function getChildrenAndAvailableProductsDataSet(
        AbstractAssociatedProduct $product,
        Language $language,
        array $bindingAttributes
    ): DataSetInterface;

    /**
     * @return array
     */
    public function findProductIdByProductChildrenId(ProductId $id): array;
}
