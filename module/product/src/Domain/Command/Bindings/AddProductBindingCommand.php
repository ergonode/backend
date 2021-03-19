<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Command\Bindings;

use Ergonode\Product\Domain\Command\ProductCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\Entity\AbstractAssociatedProduct;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

class AddProductBindingCommand implements ProductCommandInterface
{
    private ProductId $id;

    private AttributeId $bindingId;

    public function __construct(AbstractAssociatedProduct $product, AttributeId $attributeId)
    {
        $this->id = $product->getId();
        $this->bindingId = $attributeId;
    }

    public function getId(): ProductId
    {
        return $this->id;
    }

    public function getBindingId(): AttributeId
    {
        return $this->bindingId;
    }
}
