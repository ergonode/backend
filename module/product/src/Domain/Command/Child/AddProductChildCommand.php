<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Command\Child;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\Entity\VariableProduct;

/**
 */
class AddProductChildCommand implements DomainCommandInterface
{
    /**
     * @var ProductId $productId
     */
    private ProductId $productId;

    /**
     * @var ProductId $childId
     */
    private ProductId $childId;

    /**
     * @param ProductId $productId
     * @param ProductId $childId
     */
    public function __construct(ProductId $productId, ProductId $childId)
    {
        $this->productId = $productId;
        $this->childId = $childId;
    }

    /**
     * @return ProductId
     */
    public function getProductId(): ProductId
    {
        return $this->productId;
    }

    /**
     * @return ProductId
     */
    public function getChildId(): ProductId
    {
        return $this->childId;
    }
}
