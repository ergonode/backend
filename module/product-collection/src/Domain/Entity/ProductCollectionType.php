<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Domain\Entity;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionTypeCreatedEvent;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionTypeNameChangedEvent;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionTypeCode;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ProductCollectionType extends AbstractAggregateRoot
{
    /**
     * @var ProductCollectionTypeId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId")
     */
    private ProductCollectionTypeId $id;

    /**
     * @var ProductCollectionTypeCode
     *
     * @JMS\Type("Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionTypeCode")
     *
     */
    private ProductCollectionTypeCode $code;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $name;

    /**
     * @param ProductCollectionTypeId   $id
     * @param ProductCollectionTypeCode $code
     * @param TranslatableString        $name
     */
    public function __construct(ProductCollectionTypeId $id, ProductCollectionTypeCode $code, TranslatableString $name)
    {
        $this->apply(new ProductCollectionTypeCreatedEvent($id, $code, $name));
    }

    /**
     * @return ProductCollectionTypeId
     */
    public function getId(): ProductCollectionTypeId
    {
        return $this->id;
    }

    /**
     * @return ProductCollectionTypeCode
     */
    public function getCode(): ProductCollectionTypeCode
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
     * @param TranslatableString $newName
     *
     * @throws \Exception
     */
    public function changeName(TranslatableString $newName): void
    {
        if ($this->name->getTranslations() !== $newName->getTranslations()) {
            $this->apply(new ProductCollectionTypeNameChangedEvent($this->id, $this->name, $newName));
        }
    }

    /**
     * @param ProductCollectionTypeCreatedEvent $event
     */
    public function applyProductCollectionTypeCreatedEvent(ProductCollectionTypeCreatedEvent $event): void
    {
        $this->id = $event->getAggregateId();
        $this->code = $event->getCode();
        $this->name = $event->getName();
    }

    /**
     * @param ProductCollectionTypeNameChangedEvent $event
     */
    public function applyProductCollectionTypeNameChangedEvent(ProductCollectionTypeNameChangedEvent $event): void
    {
        $this->name = $event->getTo();
    }
}
