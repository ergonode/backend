<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Query;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Product\Domain\Entity\AbstractAssociatedProduct;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

interface ProductChildrenQueryInterface
{
    /**
     * @param ProductId $productId
     * @param Language  $language
     *
     * @return DataSetInterface
     */
    public function getDataSet(ProductId $productId, Language $language): DataSetInterface;

    /**
     * @param AbstractAssociatedProduct $product
     * @param Language                  $language
     * @param AbstractAttribute[]       $bindingAttributes
     *
     * @return DataSetInterface
     */
    public function getChildrenAndAvailableProductsDataSet(
        AbstractAssociatedProduct $product,
        Language $language,
        array $bindingAttributes
    ): DataSetInterface;

    /**
     * @param ProductId $id
     *
     * @return array
     */
    public function findProductIdByProductChildrenId(ProductId $id): array;
}
