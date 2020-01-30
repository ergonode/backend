<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Domain\Query;

use Ergonode\Grid\DataSetInterface;

/**
 */
interface ProductCollectionElementQueryInterface
{
    /**
     * @return DataSetInterface
     */
    public function getDataSet(): DataSetInterface;
}
