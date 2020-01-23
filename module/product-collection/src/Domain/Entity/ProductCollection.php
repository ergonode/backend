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
use Ergonode\ProductCollection\Domain\Event\ProductCollectionNameChangedEvent;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionProductCollectionElementAddedEvent;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionProductCollectionElementRemovedEvent;
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
     * @var ProductCollectionElement[]
     *
     * @JMS\Type("array<string, Ergonode\ProductCollection\Entity\ProductCollectionElement>")
     */
    private array $productCollectionElements;

    /**
     * @param ProductCollectionId     $id
     * @param ProductCollectionCode   $code
     * @param TranslatableString      $name
     * @param ProductCollectionTypeId $typeId
     * @param bool                    $allVisible
     */
    public function __construct(
        ProductCollectionId $id,
        ProductCollectionCode $code,
        TranslatableString $name,
        ProductCollectionTypeId $typeId,
        bool $allVisible
    ) {

        $this->apply(new ProductCollectionCreatedEvent($id, $code, $name, $typeId, $allVisible));
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
     * @return bool
     */
    public function isAllVisible(): bool
    {
        return $this->allVisible;
    }

    /**
     * @param TranslatableString $newName
     *
     * @throws \Exception
     */
    public function changeName(TranslatableString $newName): void
    {
        if ($this->name->getTranslations() !== $newName->getTranslations()) {
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
        if ($this->typeId->getValue() !== $newType->getValue()) {
            $this->apply(new ProductCollectionTypeIdChangedEvent($this->id, $this->getTypeId(), $newType));
        }
    }

    /**
     * @param ProductId $productId
     *
     * @return bool
     */
    public function hasProductCollectionElement(ProductId $productId): bool
    {
        foreach ($this->productCollectionElements as $productCollectionElement) {
            if ($productId->isEqual($productCollectionElement->getProductId())) {
                return true;
            }
        }
    }

    /**
     * @param ProductId $productId
     * @param bool      $visible
     */
    public function addProductCollectionElement(ProductId $productId, bool $visible): void
    {
        if ($this->hasProductCollectionElement($productId)) {
            throw new \RuntimeException(
                sprintf('Element with id "%s" is already added to collection.', $productId->getValue())
            );
        }
        $productCollectionElement = new ProductCollectionElement(
            ProductCollectionElementId::generate(),
            $productId,
            $visible
        );

        $this->apply(new ProductCollectionProductCollectionElementAddedEvent($this->id, $productCollectionElement));
    }

    /**
     * @param ProductId $productId
     */
    public function removeProductCollectionElement(ProductId $productId): void
    {
        $this->apply(new ProductCollectionProductCollectionElementRemovedEvent($this->id, $productId));
    }

    /**
     * @param ProductId $productId
     *
     * @return ProductCollectionElement
     */
    public function getProductCollectionElement(ProductId $productId): ProductCollectionElement
    {
        foreach ($this->productCollectionElements as $productCollectionElement) {
            if ($productId->isEqual($productCollectionElement->getProductId())) {
                return $productCollectionElement;
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
    public function getProductCollectionElements(): array
    {
        return $this->productCollectionElements;
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
        $this->allVisible = $event->isAllVisible();
        $this->productCollectionElements = [];
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
    protected function applyProductCollectionTypeChangedEvent(ProductCollectionTypeIdChangedEvent $event): void
    {
        $this->typeId = $event->getNewTypeId();
    }

    /**
     * @param ProductCollectionProductCollectionElementAddedEvent $event
     */
    protected function applyProductCollectionProductCollectionElementAddedEvent(
        ProductCollectionProductCollectionElementAddedEvent $event
    ): void {
        $this->productCollectionElements[$event->getProductCollectionElement()->getId()->getValue()]
            = $event->getProductCollectionElement();
    }

    /**
     * @param ProductCollectionProductCollectionElementRemovedEvent $event
     */
    protected function applyProductCollectionProductCollectionElementRemovedEvent(
        ProductCollectionProductCollectionElementRemovedEvent $event
    ): void {
        foreach ($this->productCollectionElements as $key => $productCollectionElement) {
            if ($event->getProductId()->isEqual($productCollectionElement->getProductId())) {
                unset($this->productCollectionElements[$key]);
            }
        }
    }

    /**
     * @return AbstractEntity[]
     */
    protected function getEntities(): array
    {
        return $this->productCollectionElements;
    }
}
