<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Domain\Event;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionCode;

class ProductCollectionCreatedEvent implements AggregateEventInterface
{
    private ProductCollectionId $id;

    private ProductCollectionCode $code;

    private TranslatableString $name;

    private TranslatableString $description;

    private ProductCollectionTypeId $typeId;

    private \DateTime $createdAt;

    public function __construct(
        ProductCollectionId $id,
        ProductCollectionCode $code,
        TranslatableString $name,
        TranslatableString $description,
        ProductCollectionTypeId $typeId,
        \DateTime $createdAt
    ) {
        $this->id = $id;
        $this->code = $code;
        $this->name = $name;
        $this->description = $description;
        $this->typeId = $typeId;
        $this->createdAt = $createdAt;
    }


    public function getAggregateId(): ProductCollectionId
    {
        return $this->id;
    }

    public function getDescription(): TranslatableString
    {
        return $this->description;
    }

    public function getCode(): ProductCollectionCode
    {
        return $this->code;
    }

    public function getName(): TranslatableString
    {
        return $this->name;
    }

    public function getTypeId(): ProductCollectionTypeId
    {
        return $this->typeId;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}
