<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

class DeleteProductCommand implements ProductCommandInterface
{
    private ProductId $id;

    public function __construct(ProductId $id)
    {
        $this->id = $id;
    }

    public function getId(): ProductId
    {
        return $this->id;
    }
}
