<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Domain\Command;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;

class UpdateProductCollectionTypeCommand implements ProductCollectionCommandInterface
{
    private ProductCollectionTypeId $id;

    private TranslatableString $name;

    public function __construct(
        ProductCollectionTypeId $id,
        TranslatableString $name
    ) {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): ProductCollectionTypeId
    {
        return $this->id;
    }

    public function getName(): TranslatableString
    {
        return $this->name;
    }
}
