<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Domain\Entity;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionTypeCreatedEvent;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionTypeNameChangedEvent;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionTypeCode;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;

class ProductCollectionType extends AbstractAggregateRoot
{
    private ProductCollectionTypeId $id;

    private ProductCollectionTypeCode $code;

    private TranslatableString $name;

    public function __construct(ProductCollectionTypeId $id, ProductCollectionTypeCode $code, TranslatableString $name)
    {
        $this->apply(new ProductCollectionTypeCreatedEvent($id, $code, $name));
    }

    public function getId(): ProductCollectionTypeId
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

    /**
     * @throws \Exception
     */
    public function changeName(TranslatableString $newName): void
    {
        if ($this->name->getTranslations() !== $newName->getTranslations()) {
            $this->apply(new ProductCollectionTypeNameChangedEvent($this->id, $newName));
        }
    }

    public function applyProductCollectionTypeCreatedEvent(ProductCollectionTypeCreatedEvent $event): void
    {
        $this->id = $event->getAggregateId();
        $this->code = $event->getCode();
        $this->name = $event->getName();
    }

    public function applyProductCollectionTypeNameChangedEvent(ProductCollectionTypeNameChangedEvent $event): void
    {
        $this->name = $event->getTo();
    }
}
