<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Domain\Entity;

use Ergonode\EventSourcing\Domain\AbstractEntity;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionElementId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionElementVisibleChangedEvent;
use JMS\Serializer\Annotation as JMS;

class ProductCollectionElement extends AbstractEntity
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionElementId")
     */
    private ProductCollectionElementId $id;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductId")
     */
    private ProductId $productId;

    /**
     * @JMS\Type("bool")
     */
    private bool $visible;

    /**
     * @JMS\Type("DateTime")
     */
    private \DateTime $createdAt;

    public function __construct(
        ProductCollectionElementId $id,
        ProductId $productId,
        bool $visible,
        \DateTime $createdAt
    ) {
        $this->id = $id;
        $this->productId = $productId;
        $this->visible = $visible;
        $this->createdAt = $createdAt;
    }

    public function getId(): ProductCollectionElementId
    {
        return $this->id;
    }

    public function getProductId(): ProductId
    {
        return $this->productId;
    }

    public function isVisible(): bool
    {
        return $this->visible;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function changeVisible(bool $newVisible): void
    {

        if ($this->visible !== $newVisible) {
            $this->apply(
                new ProductCollectionElementVisibleChangedEvent(
                    $this->aggregateRoot->getId(),
                    $this->productId,
                    $newVisible
                )
            );
        }
    }

    protected function applyProductCollectionElementVisibleChangedEvent(
        ProductCollectionElementVisibleChangedEvent $event
    ): void {
        if ($this->productId->isEqual($event->getProductId())) {
            $this->visible = $event->isVisible();
        }
    }
}
