<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Domain\Command;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionTypeCode;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;

class CreateProductCollectionTypeCommand implements ProductCollectionCommandInterface
{
    private ProductCollectionTypeId $id;

    private ProductCollectionTypeCode $code;

    private TranslatableString $name;

    /**
     * @throws \Exception
     */
    public function __construct(
        ProductCollectionTypeCode $code,
        TranslatableString $name
    ) {
        $this->id = ProductCollectionTypeId::generate();
        $this->code = $code;
        $this->name = $name;
    }

    public function getId(): ProductCollectionTypeId
    {
        return $this->id;
    }

    public function getCode(): ProductCollectionTypeCode
    {
        return $this->code;
    }

    public function getName(): TranslatableString
    {
        return $this->name;
    }
}
