<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Command\Child;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\Entity\SimpleProduct;
use Ergonode\Product\Domain\Entity\VariableProduct;

/**
 */
class RemoveProductChildCommand implements DomainCommandInterface
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
     * @param VariableProduct $product
     * @param SimpleProduct   $child
     */
    public function __construct(VariableProduct $product, SimpleProduct $child)
    {
        $this->productId = $product->getId();
        $this->childId = $child->getId();
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
