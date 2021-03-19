<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Domain\Command;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;

class UpdateProductCollectionCommand implements ProductCollectionCommandInterface
{
    private ProductCollectionId $id;

    private TranslatableString $name;

    private TranslatableString $description;

    private ProductCollectionTypeId $typeId;

    public function __construct(
        ProductCollectionId $id,
        TranslatableString $name,
        TranslatableString $description,
        ProductCollectionTypeId $typeId
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->typeId = $typeId;
    }

    public function getId(): ProductCollectionId
    {
        return $this->id;
    }

    public function getName(): TranslatableString
    {
        return $this->name;
    }

    public function getDescription(): TranslatableString
    {
        return $this->description;
    }

    public function getTypeId(): ProductCollectionTypeId
    {
        return $this->typeId;
    }
}
