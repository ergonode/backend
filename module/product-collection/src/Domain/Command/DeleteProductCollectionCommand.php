<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;

class DeleteProductCollectionCommand implements ProductCollectionCommandInterface
{
    private ProductCollectionId $id;

    public function __construct(ProductCollectionId $id)
    {
        $this->id = $id;
    }

    public function getId(): ProductCollectionId
    {
        return $this->id;
    }
}
