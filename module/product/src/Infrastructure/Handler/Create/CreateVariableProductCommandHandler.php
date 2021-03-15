<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Handler\Create;

use Ergonode\Product\Domain\Entity\VariableProduct;
use Ergonode\Product\Domain\Command\Create\CreateVariableProductCommand;
use Ergonode\Product\Domain\Factory\ProductFactoryInterface;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;

class CreateVariableProductCommandHandler
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
    public function __invoke(CreateVariableProductCommand $command): void
    {
        $product = $this->productFactory->create(
            VariableProduct::TYPE,
            $command->getId(),
            $command->getSku(),
            $command->getTemplateId(),
            $command->getCategories(),
            $command->getAttributes(),
        );

        $this->productRepository->save($product);
    }
}
