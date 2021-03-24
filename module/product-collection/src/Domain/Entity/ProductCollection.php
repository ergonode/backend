<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Domain\Entity;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\EventSourcing\Domain\AbstractEntity;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionCreatedEvent;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionDescriptionChangedEvent;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionElementAddedEvent;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionElementRemovedEvent;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionNameChangedEvent;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionTypeIdChangedEvent;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionCode;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionElementId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

class ProductCollection extends AbstractAggregateRoot
{
    private ProductCollectionId $id;

    private ProductCollectionCode $code;

    private TranslatableString $name;

    private TranslatableString $description;

    private ProductCollectionTypeId $typeId;

    /**
     * @var ProductCollectionElement[]
     */
    private array $elements;

    private \DateTime $createdAt;

    private ?\DateTime $editedAt = null;

    public function __construct(
        ProductCollectionId $id,
        ProductCollectionCode $code,
        TranslatableString $name,
        TranslatableString $description,
        ProductCollectionTypeId $typeId
    ) {
        $this->apply(new ProductCollectionCreatedEvent($id, $code, $name, $description, $typeId, new \DateTime()));
    }

    public function getDescription(): TranslatableString
    {
        return $this->description;
    }

    public function getId(): ProductCollectionId
    {
        return $this->id;
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

    public function getEditedAt(): ?\DateTime
    {
        return $this->editedAt;
    }

    /**
     * @throws \Exception
     */
    public function changeName(TranslatableString $newName): void
    {
        if (!$this->name->isEqual($newName)) {
            $this->apply(new ProductCollectionNameChangedEvent($this->id, $newName, new \DateTime()));
        }
    }

    /**
     * @throws \Exception
     */
    public function changeDescription(TranslatableString $newDescription): void
    {
        if (!$this->description->isEqual($newDescription)) {
            $this->apply(new ProductCollectionDescriptionChangedEvent(
                $this->id,
                $newDescription,
                new \DateTime()
            ));
        }
    }

    /**
     * @throws \Exception
     */
    public function changeType(ProductCollectionTypeId $newType): void
    {
        if (!$this->typeId->isEqual($newType)) {
            $this->apply(new ProductCollectionTypeIdChangedEvent(
                $this->id,
                $newType,
                new \DateTime()
            ));
        }
    }

    public function hasElement(ProductId $productId): bool
    {
        foreach ($this->elements as $element) {
            if ($productId->isEqual($element->getProductId())) {
                return true;
            }
        }

        return false;
    }

    public function addElement(ProductId $productId, bool $visible): void
    {
        if ($this->hasElement($productId)) {
            throw new \RuntimeException(
                sprintf('Element with id "%s" is already added to collection.', $productId->getValue())
            );
        }
        $currentDateTime = new \DateTime();
        $element = new ProductCollectionElement(
            ProductCollectionElementId::generate(),
            $productId,
            $visible,
            $currentDateTime
        );
        $this->apply(new ProductCollectionElementAddedEvent($this->id, $element, $currentDateTime));
    }

    /**
     * @param array $productIds
     */
    public function addElements(array $productIds, bool $visible = true): void
    {
        foreach ($productIds as $productId) {
            if (!$this->hasElement($productId)) {
                $currentDateTime = new \DateTime();
                $element = new ProductCollectionElement(
                    ProductCollectionElementId::generate(),
                    $productId,
                    $visible,
                    $currentDateTime
                );
                $this->apply(new ProductCollectionElementAddedEvent($this->id, $element, $currentDateTime));
            }
        }
    }

    public function removeElement(ProductId $productId): void
    {
        $this->apply(new ProductCollectionElementRemovedEvent($this->id, $productId, new \DateTime()));
    }

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

    protected function applyProductCollectionCreatedEvent(ProductCollectionCreatedEvent $event): void
    {
        $this->id = $event->getAggregateId();
        $this->code = $event->getCode();
        $this->name = $event->getName();
        $this->description = $event->getDescription();
        $this->typeId = $event->getTypeId();
        $this->createdAt = $event->getCreatedAt();
        $this->elements = [];
    }

    protected function applyProductCollectionNameChangedEvent(ProductCollectionNameChangedEvent $event): void
    {
        $this->name = $event->getTo();
        $this->editedAt = $event->getEditedAt();
    }

    protected function applyProductCollectionDescriptionChangedEvent(
        ProductCollectionDescriptionChangedEvent $event
    ): void {
        $this->description = $event->getTo();
        $this->editedAt = $event->getEditedAt();
    }

    protected function applyProductCollectionTypeIdChangedEvent(ProductCollectionTypeIdChangedEvent $event): void
    {
        $this->typeId = $event->getNewTypeId();
        $this->editedAt = $event->getEditedAt();
    }

    protected function applyProductCollectionElementAddedEvent(
        ProductCollectionElementAddedEvent $event
    ): void {
        $this->elements[$event->getElement()->getId()->getValue()]
            = $event->getElement();
        $this->editedAt = $event->getCurrentDateTime();
    }

    protected function applyProductCollectionElementRemovedEvent(
        ProductCollectionElementRemovedEvent $event
    ): void {
        foreach ($this->elements as $key => $element) {
            if ($event->getProductId()->isEqual($element->getProductId())) {
                unset($this->elements[$key]);
            }
        }
        $this->editedAt = $event->getCollectionEditedAt();
    }

    /**
     * @return AbstractEntity[]
     */
    protected function getEntities(): array
    {
        return $this->elements;
    }
}
