<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Handler\Create;

use Ergonode\Product\Domain\Entity\GroupingProduct;
use Ergonode\Product\Domain\Command\Create\CreateGroupingProductCommand;
use Ergonode\Product\Domain\Factory\ProductFactoryInterface;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;

class CreateGroupingProductCommandHandler
{
    protected ProductRepositoryInterface $productRepository;

    protected ProductFactoryInterface $productFactory;

    public function __construct(ProductRepositoryInterface $productRepository, ProductFactoryInterface $productFactory)
    {
        $this->productRepository = $productRepository;
        $this->productFactory = $productFactory;
    }
    /**
     * @throws \Exception
     */
    public function __invoke(CreateGroupingProductCommand $command): void
    {
        $product = $this->productFactory->create(
            GroupingProduct::TYPE,
            $command->getId(),
            $command->getSku(),
            $command->getTemplateId(),
            $command->getCategories(),
            $command->getAttributes(),
        );

        $this->productRepository->save($product);
    }
}
