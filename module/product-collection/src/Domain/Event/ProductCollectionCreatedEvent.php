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
     * @JMS\Type("Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionCode")
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
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $description;

    /**
     * @var ProductCollectionTypeId
     *
     * @JMS\Type("Ergonode\ProductCollection\Domain\Entity\ProductCollectionTypeId")
     */
    private ProductCollectionTypeId $typeId;

    /**
     * @var \DateTime $createdAt
     *
     * @JMS\Type("DateTime")
     */
    private \DateTime $createdAt;

    /**
     * ProductCollectionCreatedEvent constructor.
     *
     * @param ProductCollectionId     $id
     * @param ProductCollectionCode   $code
     * @param TranslatableString      $name
     * @param TranslatableString      $description
     * @param ProductCollectionTypeId $typeId
     * @param \DateTime               $createdAt
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


    /**
     * @return ProductCollectionId
     */
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }

    /**
     * @return TranslatableString
     */
    public function getDescription(): TranslatableString
    {
        return $this->description;
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
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}
