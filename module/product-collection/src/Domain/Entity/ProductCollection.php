<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Domain\Entity;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\EventSourcing\Domain\AbstractEntity;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionElementId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionCreatedEvent;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionDescriptionChangedEvent;
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
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId")
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
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId")
     */
    private ProductCollectionTypeId $typeId;

    /**
     * @var ProductCollectionElement[]
     *
     * @JMS\Type("array<string, Ergonode\ProductCollection\Domain\Entity\ProductCollectionElement>")
     */
    private array $elements;

    /**
     * @var \DateTime $createdAt
     *
     * @JMS\Type("DateTime")
     */
    private \DateTime $createdAt;

    /**
     * @var \DateTime | null $editedAt
     *
     * @JMS\Type("DateTime")
     */
    private ?\DateTime $editedAt = null;

    /**
     * @param ProductCollectionId     $id
     * @param ProductCollectionCode   $code
     * @param TranslatableString      $name
     * @param TranslatableString      $description
     * @param ProductCollectionTypeId $typeId
     */
    public function __construct(
        ProductCollectionId $id,
        ProductCollectionCode $code,
        TranslatableString $name,
        TranslatableString $description,
        ProductCollectionTypeId $typeId
    ) {
        $this->apply(new ProductCollectionCreatedEvent($id, $code, $name, $description, $typeId, new \DateTime()));
    }

    /**
     * @return TranslatableString
     */
    public function getDescription(): TranslatableString
    {
        return $this->description;
    }

    /**
     * @return ProductCollectionId
     */
    public function getId(): ProductCollectionId
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
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime|null
     */
    public function getEditedAt(): ?\DateTime
    {
        return $this->editedAt;
    }

    /**
     * @param TranslatableString $newName
     *
     * @throws \Exception
     */
    public function changeName(TranslatableString $newName): void
    {
        if (!$this->name->isEqual($newName)) {
            $this->apply(new ProductCollectionNameChangedEvent($this->id, $this->name, $newName, new \DateTime()));
        }
    }

    /**
     * @param TranslatableString $newDescription
     *
     * @throws \Exception
     */
    public function changeDescription(TranslatableString $newDescription): void
    {
        if (!$this->description->isEqual($newDescription)) {
            $this->apply(new ProductCollectionDescriptionChangedEvent(
                $this->id,
                $this->description,
                $newDescription,
                new \DateTime()
            ));
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
            $this->apply(new ProductCollectionTypeIdChangedEvent(
                $this->id,
                $this->getTypeId(),
                $newType,
                new \DateTime()
            ));
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
     * @param ProductId $productId
     */
    public function removeElement(ProductId $productId): void
    {
        $this->apply(new ProductCollectionElementRemovedEvent($this->id, $productId, new \DateTime()));
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
        $this->description = $event->getDescription();
        $this->typeId = $event->getTypeId();
        $this->createdAt = $event->getCreatedAt();
        $this->elements = [];
    }

    /**
     * @param ProductCollectionNameChangedEvent $event
     */
    protected function applyProductCollectionNameChangedEvent(ProductCollectionNameChangedEvent $event): void
    {
        $this->name = $event->getTo();
        $this->editedAt = $event->getEditedAt();
    }

    /**
     * @param ProductCollectionDescriptionChangedEvent $event
     */
    protected function applyProductCollectionDescriptionChangedEvent(
        ProductCollectionDescriptionChangedEvent $event
    ): void {
        $this->description = $event->getTo();
        $this->editedAt = $event->getEditedAt();
    }

    /**
     * @param ProductCollectionTypeIdChangedEvent $event
     */
    protected function applyProductCollectionTypeIdChangedEvent(ProductCollectionTypeIdChangedEvent $event): void
    {
        $this->typeId = $event->getNewTypeId();
        $this->editedAt = $event->getEditedAt();
    }

    /**
     * @param ProductCollectionElementAddedEvent $event
     */
    protected function applyProductCollectionElementAddedEvent(
        ProductCollectionElementAddedEvent $event
    ): void {
        $this->elements[$event->getElement()->getId()->getValue()]
            = $event->getElement();
        $this->editedAt = $event->getCurrentDateTime();
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
