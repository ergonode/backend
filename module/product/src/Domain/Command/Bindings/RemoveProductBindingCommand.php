<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Command\Bindings;

use Ergonode\Product\Domain\Command\ProductCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

class RemoveProductBindingCommand implements ProductCommandInterface
{
    private ProductId $id;

    private AttributeId $bindingId;

    public function __construct(ProductId $productId, AttributeId $bindingId)
    {
        $this->id = $productId;
        $this->bindingId = $bindingId;
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
