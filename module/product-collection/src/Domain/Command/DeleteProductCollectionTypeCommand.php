<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;

class DeleteProductCollectionTypeCommand implements ProductCollectionCommandInterface
{
    private ProductCollectionTypeId $id;

    public function __construct(ProductCollectionTypeId $id)
    {
        $this->id = $id;
    }

    public function getId(): ProductCollectionTypeId
    {
        return $this->id;
    }
}
