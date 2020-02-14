<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Domain\Query;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

/**
 */
interface ProductCollectionQueryInterface
{
    /**
     * @param Language $language
     *
     * @return DataSetInterface
     */
    public function getDataSet(Language $language): DataSetInterface;

    /**
     * @return string[]
     */
    public function getDictionary(): array;

    /**
     * @param ProductId $productId
     *
     * @return array
     */
    public function findProductCollectionIdByProduct(ProductId $productId): array;
}
