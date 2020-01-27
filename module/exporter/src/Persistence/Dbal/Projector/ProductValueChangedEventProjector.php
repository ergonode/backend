<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Persistence\Dbal\Projector;

use Ergonode\Exporter\Domain\Exception\ProductNotFoundException;
use Ergonode\Exporter\Domain\Factory\AttributeFactory;
use Ergonode\Exporter\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Product\Domain\Event\ProductValueChangedEvent;

/**
 */
class ProductValueChangedEventProjector
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
     * ProductValueChangedEventProjector constructor.
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
        $product = $this->productRepository->load($event->getAggregateId()->getValue());
        if (null === $product) {
            throw new ProductNotFoundException($event->getAggregateId()->getValue());
        }

        $newAttribute = $this->attributeFactory->create($event->getAttributeCode()->getValue(), $event->getTo());
        $product->changeAttribute($newAttribute);
    }
}
