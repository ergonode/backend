<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Domain\Event;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionId;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionTypeId;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionCode;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ProductCollectionCreatedEvent implements DomainEventInterface
{
    /**
     * @var ProductCollectionId
     *
     * @JMS\Type("Ergonode\ProductCollection\Domain\Entity\ProductCollectionId")
     */
    private ProductCollectionId $id;

    /**
     * @var ProductCollectionCode
     *
     * @JMS/Type("Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionCode")
     *
     */
    private ProductCollectionCode $code;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $name;

    /**
     * @var ProductCollectionTypeId
     *
     * @JMS\Type("Ergonode\ProductCollection\Domain\Entity\ProductCollectionTypeId")
     */
    private ProductCollectionTypeId $typeId;

    /**
     * @var bool
     *
     * @JMS\Type("string")
     */
    private bool $allVisible;

    /**
     * @param ProductCollectionId     $id
     * @param ProductCollectionCode   $code
     * @param TranslatableString      $name
     * @param ProductCollectionTypeId $typeId
     * @param bool                    $allVisible
     */
    public function __construct(ProductCollectionId $id, ProductCollectionCode $code, TranslatableString $name, ProductCollectionTypeId $typeId, bool $allVisible)
    {
        $this->id = $id;
        $this->code = $code;
        $this->name = $name;
        $this->typeId = $typeId;
        $this->allVisible = $allVisible;
    }

    /**
     * @return ProductCollectionId
     */
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }

    /**
     * @return ProductCollectionCode
     */
    public function getCode(): ProductCollectionCode
    {
        return $this->code;
    }

    /**
     * @return TranslatableString
     */
    public function getName(): TranslatableString
    {
        return $this->name;
    }

    /**
     * @return ProductCollectionTypeId
     */
    public function getTypeId(): ProductCollectionTypeId
    {
        return $this->typeId;
    }

    /**
     * @return bool
     */
    public function isAllVisible(): bool
    {
        return $this->allVisible;
    }
}
