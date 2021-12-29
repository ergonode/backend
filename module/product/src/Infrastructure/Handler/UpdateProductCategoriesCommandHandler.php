<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Handler;

use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Product\Domain\Command\UpdateProductCategoriesCommand;

class UpdateProductCategoriesCommandHandler
{
    private ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(UpdateProductCategoriesCommand $command): void
    {
        $product = $this->productRepository->load($command->getId());
        Assert::notNull($product);

        $product->changeCategories($command->getCategories());

        $this->productRepository->save($product);
    }
}
