<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Domain\Query;

use Ergonode\Grid\DataSetInterface;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionId;

/**
 */
interface ProductCollectionElementQueryInterface
{
    /**
     * @param ProductCollectionId $productCollectionId
     *
     * @return DataSetInterface
     */
    public function getDataSet(ProductCollectionId $productCollectionId): DataSetInterface;
}
