<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Domain\Event;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionCode;
use JMS\Serializer\Annotation as JMS;

class ProductCollectionCreatedEvent implements AggregateEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId")
     */
    private ProductCollectionId $id;

    /**
     * @JMS\Type("Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionCode")
     */
    private ProductCollectionCode $code;

    /**
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $name;

    /**
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $description;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId")
     */
    private ProductCollectionTypeId $typeId;

    /**
     * @JMS\Type("DateTime")
     */
    private \DateTime $createdAt;

    /**
     * ProductCollectionCreatedEvent constructor.
     */
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
