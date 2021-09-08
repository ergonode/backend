<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Command\Relations;

use Ergonode\Product\Domain\Command\ProductCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

class AddProductChildCommand implements ProductCommandInterface
{
    private ProductId $id;

    private ProductId $childId;

    public function __construct(ProductId $productId, ProductId $childId)
    {
        $this->id = $productId;
        $this->childId = $childId;
    }

    public function getId(): ProductId
    {
        return $this->id;
    }

    public function getChildId(): ProductId
    {
        return $this->childId;
    }
}
