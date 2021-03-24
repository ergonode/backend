<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;

class DeleteProductCollectionElementCommand implements ProductCollectionCommandInterface
{
    private ProductCollectionId $productCollectionId;

    private ProductId $productId;

    public function __construct(ProductCollectionId $productCollectionId, ProductId $productId)
    {
        $this->productCollectionId = $productCollectionId;
        $this->productId = $productId;
    }

    public function getProductCollectionId(): ProductCollectionId
    {
        return $this->productCollectionId;
    }

    public function getProductId(): ProductId
    {
        return $this->productId;
    }
}
