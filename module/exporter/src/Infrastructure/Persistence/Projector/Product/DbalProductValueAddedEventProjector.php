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
use Ergonode\Product\Domain\Event\ProductValueAddedEvent;
use Ramsey\Uuid\Uuid;

/**
 */
class DbalProductValueAddedEventProjector
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
     * @param ProductValueAddedEvent $event
     *
     * @throws ProductNotFoundException
     */
    public function __invoke(ProductValueAddedEvent $event): void
    {
        $id = Uuid::fromString($event->getAggregateId()->getValue());
        $product = $this->productRepository->load($id);
        if (null === $product) {
            throw new ProductNotFoundException($event->getAggregateId()->getValue());
        }

        $product->addAttribute(
            $this->attributeFactory->create($event->getAttributeCode()->getValue(), $event->getValue())
        );
        $this->productRepository->save($product);
    }
}
