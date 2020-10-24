<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Domain\Event;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionTypeCode;
use JMS\Serializer\Annotation as JMS;

class ProductCollectionTypeCreatedEvent implements DomainEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId")
     */
    private ProductCollectionTypeId $id;

    /**
     * @JMS\Type("Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionTypeCode")
     */
    private ProductCollectionTypeCode $code;

    /**
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $name;

    /**
     * ProductCollectionTypeCreatedEvent constructor.
     */
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
