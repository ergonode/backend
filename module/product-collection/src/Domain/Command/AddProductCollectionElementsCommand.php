<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

class AddProductCollectionElementsCommand implements ProductCollectionCommandInterface
{
    private ProductCollectionId $productCollectionId;

    /**
     * @var ProductId[]
     */
    private array $productIds;

    /**
     * @param ProductId[] $productIds
     */
    public function __construct(ProductCollectionId $productCollectionId, array $productIds)
    {
        $this->productCollectionId = $productCollectionId;
        $this->productIds = $productIds;
    }

    public function getProductCollectionId(): ProductCollectionId
    {
        return $this->productCollectionId;
    }

    /**
     * @return ProductId[]
     */
    public function getProductIds(): array
    {
        return $this->productIds;
    }
}
