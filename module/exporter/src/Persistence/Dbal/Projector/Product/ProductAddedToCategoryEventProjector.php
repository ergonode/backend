<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Persistence\Dbal\Projector\Product;

use Ergonode\Exporter\Domain\Exception\ProductNotFoundException;
use Ergonode\Exporter\Domain\Factory\Catalog\CategoryCodeFactory;
use Ergonode\Exporter\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Product\Domain\Event\ProductAddedToCategoryEvent;
use Ramsey\Uuid\Uuid;

/**
 */
class ProductAddedToCategoryEventProjector
{
    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @var CategoryCodeFactory
     */
    private CategoryCodeFactory $categoryCodeFactory;

    /**
     * ProductAddedToCategoryEventProjector constructor.
     * @param ProductRepositoryInterface $productRepository
     * @param CategoryCodeFactory        $categoryCodeFactory
     */
    public function __construct(ProductRepositoryInterface $productRepository, CategoryCodeFactory $categoryCodeFactory)
    {
        $this->productRepository = $productRepository;
        $this->categoryCodeFactory = $categoryCodeFactory;
    }

    /**
     * @param ProductAddedToCategoryEvent $event
     *
     * @throws ProductNotFoundException
     */
    public function __invoke(ProductAddedToCategoryEvent $event): void
    {
        $id = Uuid::fromString($event->getAggregateId()->getValue());
        $product = $this->productRepository->load($id);
        if (null === $product) {
            throw new ProductNotFoundException($event->getAggregateId()->getValue());
        }

        $product->addCategory($this->categoryCodeFactory->create($event->getCategoryCode()->getValue()));
        $this->productRepository->save($product);
    }
}
