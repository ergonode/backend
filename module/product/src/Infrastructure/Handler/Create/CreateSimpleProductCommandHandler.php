<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Handler\Create;

use Ergonode\Product\Domain\Entity\SimpleProduct;
use Ergonode\Product\Domain\Command\Create\CreateSimpleProductCommand;
use Ergonode\Product\Domain\Factory\ProductFactoryInterface;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;

class CreateSimpleProductCommandHandler
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
    public function __invoke(CreateSimpleProductCommand $command): void
    {
        $product = $this->productFactory->create(
            SimpleProduct::TYPE,
            $command->getId(),
            $command->getSku(),
            $command->getTemplateId(),
            $command->getCategories(),
            $command->getAttributes(),
        );

        $this->productRepository->save($product);
    }
}
