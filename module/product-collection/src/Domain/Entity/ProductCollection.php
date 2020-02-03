<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Domain\Entity;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\EventSourcing\Domain\AbstractEntity;
use Ergonode\Product\Domain\Entity\ProductId;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionCreatedEvent;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionElementAddedEvent;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionElementRemovedEvent;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionNameChangedEvent;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionTypeIdChangedEvent;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionCode;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ProductCollection extends AbstractAggregateRoot
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
     * @var ProductCollectionTypeId
     *
     * @JMS\Type("Ergonode\ProductCollection\Domain\Entity\ProductCollectionTypeId")
     */
    private ProductCollectionTypeId $typeId;

    /**
     * @var ProductCollectionElement[]
     *
     * @JMS\Type("array<string, Ergonode\ProductCollection\Domain\Entity\ProductCollectionElement>")
     */
    private array $elements;

    /**
     * @param ProductCollectionId     $id
     * @param ProductCollectionCode   $code
     * @param TranslatableString      $name
     * @param ProductCollectionTypeId $typeId
     */
    public function __construct(
        ProductCollectionId $id,
        ProductCollectionCode $code,
        TranslatableString $name,
        ProductCollectionTypeId $typeId
    ) {

        $this->apply(new ProductCollectionCreatedEvent($id, $code, $name, $typeId));
    }

    /**
     * @return ProductCollectionId
     */
    public function getId(): AbstractId
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
     * @param TranslatableString $newName
     *
     * @throws \Exception
     */
    public function changeName(TranslatableString $newName): void
    {
        if (!$this->name->isEqual($newName)) {
            $this->apply(new ProductCollectionNameChangedEvent($this->id, $this->name, $newName));
        }
    }

    /**
     * @param ProductCollectionTypeId $newType
     *
     * @throws \Exception
     */
    public function changeType(ProductCollectionTypeId $newType): void
    {
        if (!$this->typeId->isEqual($newType)) {
            $this->apply(new ProductCollectionTypeIdChangedEvent($this->id, $this->getTypeId(), $newType));
        }
    }

    /**
     * @param ProductId $productId
     *
     * @return bool
     */
    public function hasElement(ProductId $productId): bool
    {
        foreach ($this->elements as $element) {
            if ($productId->isEqual($element->getProductId())) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param ProductId $productId
     * @param bool      $visible
     */
    public function addElement(ProductId $productId, bool $visible): void
    {
        if ($this->hasElement($productId)) {
            throw new \RuntimeException(
                sprintf('Element with id "%s" is already added to collection.', $productId->getValue())
            );
        }
        $element = new ProductCollectionElement(
            ProductCollectionElementId::generate(),
            $productId,
            $visible
        );
        $this->apply(new ProductCollectionElementAddedEvent($this->id, $element));
    }

    /**
     * @param ProductId $productId
     */
    public function removeElement(ProductId $productId): void
    {
        $this->apply(new ProductCollectionElementRemovedEvent($this->id, $productId));
    }

    /**
     * @param ProductId $productId
     *
     * @return ProductCollectionElement
     */
    public function getElement(ProductId $productId): ProductCollectionElement
    {
        foreach ($this->elements as $element) {
            if ($productId->isEqual($element->getProductId())) {
                return $element;
            }
        }
        throw new \RuntimeException(sprintf(
            'Element with id "%s" doesn\'t exist in the collection.',
            $productId->getValue()
        ));
    }

    /**
     * @return ProductCollectionElement[]
     */
    public function getElements(): array
    {
        return $this->elements;
    }

    /**
     * @param ProductCollectionCreatedEvent $event
     */
    protected function applyProductCollectionCreatedEvent(ProductCollectionCreatedEvent $event): void
    {
        $this->id = $event->getAggregateId();
        $this->code = $event->getCode();
        $this->name = $event->getName();
        $this->typeId = $event->getTypeId();
        $this->elements = [];
    }

    /**
     * @param ProductCollectionNameChangedEvent $event
     */
    protected function applyProductCollectionNameChangedEvent(ProductCollectionNameChangedEvent $event): void
    {
        $this->name = $event->getTo();
    }

    /**
     * @param ProductCollectionTypeIdChangedEvent $event
     */
    protected function applyProductCollectionTypeIdChangedEvent(ProductCollectionTypeIdChangedEvent $event): void
    {
        $this->typeId = $event->getNewTypeId();
    }

    /**
     * @param ProductCollectionElementAddedEvent $event
     */
    protected function applyProductCollectionElementAddedEvent(
        ProductCollectionElementAddedEvent $event
    ): void {
        $this->elements[$event->getElement()->getId()->getValue()]
            = $event->getElement();
    }

    /**
     * @param ProductCollectionElementRemovedEvent $event
     */
    protected function applyProductCollectionElementRemovedEvent(
        ProductCollectionElementRemovedEvent $event
    ): void {
        foreach ($this->elements as $key => $element) {
            if ($event->getProductId()->isEqual($element->getProductId())) {
                unset($this->elements[$key]);
            }
        }
    }

    /**
     * @return AbstractEntity[]
     */
    protected function getEntities(): array
    {
        return $this->elements;
    }
}
