<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Infrastructure\Persistence\Projector\Product;

use Ergonode\Exporter\Domain\Exception\ProductNotFoundException;
use Ergonode\Exporter\Domain\Factory\Catalog\AttributeFactory;
use Ergonode\Exporter\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Product\Domain\Event\ProductValueChangedEvent;
use Ramsey\Uuid\Uuid;

/**
 */
class DbalProductValueChangedEventProjector
{
    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @var AttributeFactory
     */
    private AttributeFactory $attributeFactory;

    /**
     * @param ProductRepositoryInterface $productRepository
     * @param AttributeFactory           $attributeFactory
     */
    public function __construct(ProductRepositoryInterface $productRepository, AttributeFactory $attributeFactory)
    {
        $this->productRepository = $productRepository;
        $this->attributeFactory = $attributeFactory;
    }

    /**
     * @param ProductValueChangedEvent $event
     *
     * @throws ProductNotFoundException
     */
    public function __invoke(ProductValueChangedEvent $event): void
    {
        $id = Uuid::fromString($event->getAggregateId()->getValue());
        $product = $this->productRepository->load($id);
        if (null === $product) {
            throw new ProductNotFoundException($event->getAggregateId()->getValue());
        }

        $newAttribute = $this->attributeFactory->create($event->getAttributeCode()->getValue(), $event->getTo());
        $product->changeAttribute($newAttribute);
    }
}
