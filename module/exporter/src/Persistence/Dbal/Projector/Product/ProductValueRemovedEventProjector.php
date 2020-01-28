<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Persistence\Dbal\Projector\Product;

use Ergonode\Exporter\Domain\Exception\ProductNotFoundException;
use Ergonode\Exporter\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Product\Domain\Event\ProductValueRemovedEvent;

/**
 */
class ProductValueRemovedEventProjector
{
    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * ProductValueRemovedEventProjector constructor.
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param ProductValueRemovedEvent $event
     *
     * @throws ProductNotFoundException
     */
    public function __invoke(ProductValueRemovedEvent $event): void
    {
        $product = $this->productRepository->load($event->getAggregateId()->getValue());
        if (null === $product) {
            throw new ProductNotFoundException($event->getAggregateId()->getValue());
        }
        $product->removeAttribute($event->getAttributeCode()->getValue());
    }
}
