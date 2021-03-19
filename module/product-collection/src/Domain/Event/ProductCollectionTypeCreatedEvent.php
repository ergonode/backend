<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Domain\Event;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionTypeCode;

class ProductCollectionTypeCreatedEvent implements AggregateEventInterface
{
    private ProductCollectionTypeId $id;

    private ProductCollectionTypeCode $code;

    private TranslatableString $name;

    public function __construct(ProductCollectionTypeId $id, ProductCollectionTypeCode $code, TranslatableString $name)
    {
        $this->id = $id;
        $this->code = $code;
        $this->name = $name;
    }

    public function getAggregateId(): ProductCollectionTypeId
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
