<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Handler;

use Ergonode\Product\Domain\Command\CreateProductCommand;
use Ergonode\Product\Domain\Provider\ProductFactoryProvider;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Product\Domain\Entity\SimpleProduct;

/**
 */
class CreateProductCommandHandler
{
    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @var ProductFactoryProvider
     */
    private ProductFactoryProvider $productFactoryProvider;

    /**
     * @param ProductRepositoryInterface $productRepository
     * @param ProductFactoryProvider     $productFactoryProvider
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        ProductFactoryProvider $productFactoryProvider
    ) {
        $this->productRepository = $productRepository;
        $this->productFactoryProvider = $productFactoryProvider;
    }

    /**
     * @param CreateProductCommand $command
     */
    public function __invoke(CreateProductCommand $command)
    {
        $categories = $command->getCategories();

        $factory = $this->productFactoryProvider->provide(SimpleProduct::TYPE);
        $product = $factory->create($command->getId(), $command->getSku(), $categories, $command->getAttributes());

        $this->productRepository->save($product);
    }
}
